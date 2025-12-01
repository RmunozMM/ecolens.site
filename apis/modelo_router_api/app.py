# ================================================================
# 🌿 EcoLens Router + Expert Models (v3.8 - Predict-only)
# - Router general: EfficientNet-B5 (torchvision o timm, autodetección)
# - Expert: autocarga timm/torchvision según checkpoint
# - La IA SOLO predice: router + experto, sin decidir "concluyente"
# - Devuelve top-1 y top-k de especies
# - Limpieza de memoria tras cada predicción
# ================================================================

from pathlib import Path
from fastapi import FastAPI, UploadFile, File
from fastapi.responses import JSONResponse
from fastapi.middleware.cors import CORSMiddleware
from PIL import Image
import torch
import torchvision.transforms as T
from torchvision import models as tv_models
import json, io, time, random, numpy as np, gc

# ------------------------------------------------------------
# Determinismo y rendimiento
# ------------------------------------------------------------
torch.manual_seed(42)
torch.cuda.manual_seed_all(42)
np.random.seed(42)
random.seed(42)
torch.use_deterministic_algorithms(True, warn_only=True)
torch.set_grad_enabled(False)
torch.backends.cudnn.benchmark = False
torch.backends.cudnn.deterministic = True

# ------------------------------------------------------------
# Rutas
# ------------------------------------------------------------
BASE_DIR = Path(__file__).resolve().parent
MODELS_DIR = BASE_DIR / "models"
EXPERTS_DIR = MODELS_DIR / "models_experts"

# IMPORTANTE:
# Asegúrate de que estos nombres coinciden con los archivos que subiste.
# Si mantuviste el nombre del entrenamiento ("efficientnet_b5_router_best.pth"
# y "router_classes.json"), puedes:
#   - renombrarlos en el servidor a:
#       efficientnet_b5_best.pth
#       class_names.json
#   - o cambiar estas rutas para que apunten a esos nombres reales.
ROUTER_PATH = MODELS_DIR / "efficientnet_b5_best.pth"
ROUTER_CLASSES = MODELS_DIR / "class_names.json"

DEVICE = "cuda" if torch.cuda.is_available() else "cpu"
print(f"🖥️  Ejecutando inferencia en: {DEVICE}")

# ------------------------------------------------------------
# Parámetros de control (metadatos, NO control de flujo)
# ------------------------------------------------------------
THRESHOLDS = {
    "router_min_conf": 0.80,   # referencia sugerida para taxón
    "expert_min_conf": 0.85    # referencia sugerida para especie "concluyente"
}
print(f"🎚️  Thresholds de referencia: {THRESHOLDS}")

# ------------------------------------------------------------
# Configuración de expertos por taxonomía (par ordenado)
#   clave: nombre de clase taxonómica tal como llega del router (lower/strip)
#   valor: (nombre_archivo_modelo, nombre_archivo_labels)
#
# Para añadir nuevos expertos:
#   TAXON_EXPERT_CONFIG["reptilia"] = ("modelo_experto_reptiles.pth", "labels_reptiles.json")
# ------------------------------------------------------------
TAXON_EXPERT_CONFIG = {
    # Aves
    "aves": ("modelo_experto_aves.pth", "labels_aves.json"),

    # Mamíferos / Mammalia (forma “limpia” del router)
    "mammalia": ("modelo_experto_mamiferos.pth", "labels_mamiferos.json"),
}

# ------------------------------------------------------------
# Clases router
# ------------------------------------------------------------
if not ROUTER_CLASSES.exists():
    raise FileNotFoundError(f"❌ No se encontró el archivo de clases: {ROUTER_CLASSES}")

with open(ROUTER_CLASSES, "r") as f:
    data = json.load(f)

CLASSES = data if isinstance(data, list) else (
    data.get("classes7")
    or data.get("classes")
    or data.get("labels")
    or data.get("class_names", [])
)
if not CLASSES:
    raise ValueError("❌ No se encontraron clases válidas en el archivo JSON.")
print(f"✅ Clases cargadas ({len(CLASSES)}): {CLASSES}")

# ------------------------------------------------------------
# Preproceso
# ------------------------------------------------------------
ARCH_NAME = "efficientnet_b5"
IMG_SIZE = 456
NORM_MEAN = [0.485, 0.456, 0.406]
NORM_STD = [0.229, 0.224, 0.225]

transform = T.Compose([
    T.Resize((IMG_SIZE, IMG_SIZE)),
    T.ToTensor(),
    T.Normalize(NORM_MEAN, NORM_STD)
])

# ------------------------------------------------------------
# Helpers de carga
# ------------------------------------------------------------
def freeze_and_eval(model: torch.nn.Module) -> torch.nn.Module:
    for p in model.parameters():
        p.requires_grad = False
    model.eval()
    model.to(DEVICE)
    return model

def load_router_auto(model_path: Path, num_classes: int) -> torch.nn.Module:
    """
    Carga el router detectando si el checkpoint viene de:
    - torchvision.efficientnet_b5
    - timm tf_efficientnet_b5

    Esto permite usar el nuevo modelo entrenado con:
        timm.create_model('tf_efficientnet_b5', pretrained=True, num_classes=...)
    sin romper compatibilidad con un modelo anterior de torchvision.
    """
    print(f"📦 Cargando checkpoint del router desde: {model_path}")
    state = torch.load(model_path, map_location="cpu")

    if not isinstance(state, dict):
        raise RuntimeError("❌ El checkpoint del router no es un state_dict válido (dict).")

    keys = list(state.keys())
    if not keys:
        raise RuntimeError("❌ El checkpoint del router está vacío (sin parámetros).")

    k0 = keys[0]

    # Heurística: timm tf_efficientnet_b5 suele tener keys tipo:
    #   'conv_stem.weight', 'bn1.weight', 'blocks.0.0.conv_dw.weight', 'conv_head.weight', 'classifier.weight', etc.
    # Torchvision efficientnet_b5 suele empezar con 'features.0.0.weight', etc.
    is_timm = (
        "conv_stem" in k0
        or any(k.startswith(("bn1", "blocks.", "conv_head", "classifier")) for k in keys)
    )

    if is_timm:
        print("🔎 Router: detectado checkpoint TIMM (tf_efficientnet_b5)")
        import timm
        m = timm.create_model("tf_efficientnet_b5", pretrained=False, num_classes=num_classes)
        m.load_state_dict(state, strict=True)
        return freeze_and_eval(m)

    # Fallback: asumimos formato torchvision
    print("🔎 Router: detectado checkpoint TORCHVISION (efficientnet_b5)")
    m = tv_models.efficientnet_b5(weights=None)
    in_features = m.classifier[1].in_features
    m.classifier[1] = torch.nn.Linear(in_features, num_classes)
    m.load_state_dict(state, strict=True)
    return freeze_and_eval(m)

def load_expert_auto(model_path: Path, num_classes: int) -> torch.nn.Module:
    """Autodetecta si el checkpoint del experto es de torchvision o timm."""
    state = torch.load(model_path, map_location="cpu")
    keys = list(state.keys())
    k0 = keys[0] if keys else ""

    # Checkpoint estilo torchvision (efficientnet_b5)
    if "features." in k0:
        m = tv_models.efficientnet_b5(weights=None)
        in_features = m.classifier[1].in_features
        m.classifier[1] = torch.nn.Linear(in_features, num_classes)
        m.load_state_dict(state, strict=True)
        return freeze_and_eval(m)

    # Checkpoint estilo timm
    if ("conv_stem" in k0) or any(k.startswith(("bn1", "blocks.", "conv_head", "classifier")) for k in keys):
        import timm
        m = timm.create_model("efficientnet_b5", pretrained=False, num_classes=num_classes)
        m.load_state_dict(state, strict=True)
        return freeze_and_eval(m)

    raise RuntimeError("Formato de checkpoint no reconocido para el modelo experto.")

# ------------------------------------------------------------
# Cargar router
# ------------------------------------------------------------
print(f"\nCargando modelo principal {ARCH_NAME}...")
try:
    router_model = load_router_auto(ROUTER_PATH, len(CLASSES))
    print(f"✅ Modelo {ARCH_NAME} cargado correctamente.")
except Exception as e:
    print(f"❌ Error al cargar el modelo principal: {e}")
    router_model = None

# ------------------------------------------------------------
# Cache de expertos
# ------------------------------------------------------------
EXPERT_CACHE = {}

def load_expert_model(taxon_name: str):
    """
    Carga modelo experto y labels para una clase taxonómica.
    La decisión de si el resultado es "concluyente" NO se toma aquí.

    Reglas:
    1) Normaliza taxon a lower().strip().
    2) Si existe en TAXON_EXPERT_CONFIG, usa ese par (modelo, labels).
    3) Si no, conserva el comportamiento original:
       - mamíferos por substring ("mammalia"/"mamífer"/"mamifer").
       - resto: patrón {taxon_key}_expert.pth / class_names_{taxon_key}.json
    """
    taxon_key = taxon_name.lower().strip()

    # Cache por taxón normalizado
    if taxon_key in EXPERT_CACHE:
        return EXPERT_CACHE[taxon_key]

    # 1) Configuración explícita (par ordenado)
    config_pair = TAXON_EXPERT_CONFIG.get(taxon_key)
    if config_pair:
        model_filename, labels_filename = config_pair
        model_path = EXPERTS_DIR / model_filename
        class_path = EXPERTS_DIR / labels_filename
    else:
        # 2) Comportamiento heredado para mamíferos (no se toca)
        if "mammalia" in taxon_key or "mamífer" in taxon_key or "mamifer" in taxon_key:
            model_path = EXPERTS_DIR / "modelo_experto_mamiferos.pth"
            class_path = EXPERTS_DIR / "labels_mamiferos.json"
        else:
            # 3) Fallback genérico
            model_path = EXPERTS_DIR / f"{taxon_key}_expert.pth"
            class_path = EXPERTS_DIR / f"class_names_{taxon_key}.json"

    if not model_path.exists() or not class_path.exists():
        print(f"⚠️ No se encontró modelo experto para {taxon_name} (key='{taxon_key}')")
        return None, None, None

    with open(class_path, "r") as f:
        labels = json.load(f)

    if isinstance(labels, dict):
        labels = labels.get("classes") or labels.get("labels") or list(labels.values())
    if not isinstance(labels, list):
        raise ValueError(f"❌ Formato inesperado en {class_path}")

    expert_model = load_expert_auto(model_path, len(labels))
    print(f"✅ Modelo experto para {taxon_name} cargado correctamente ({len(labels)} clases).")
    EXPERT_CACHE[taxon_key] = (expert_model, labels, model_path.name)
    return EXPERT_CACHE[taxon_key]

# ------------------------------------------------------------
# FastAPI
# ------------------------------------------------------------
app = FastAPI(title="EcoLens Router + Expert API", version="3.8.0")
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"], allow_credentials=True,
    allow_methods=["*"], allow_headers=["*"],
)

# ------------------------------------------------------------
# Endpoints
# ------------------------------------------------------------
@app.get("/health")
async def health():
    return {
        "status": "ok",
        "router_model_loaded": router_model is not None,
        "router_architecture": ARCH_NAME,
        "router_classes": len(CLASSES),
        "thresholds": THRESHOLDS,
        "device": DEVICE
    }

@app.post("/predict")
async def predict(image: UploadFile = File(...)):
    if router_model is None:
        return JSONResponse({"error": "Modelo principal no cargado."}, status_code=500)

    x = router_logits = router_probs = expert_logits = expert_probs = None
    expert_model = None

    try:
        # ------------------------- 0) Preproceso imagen -------------------------
        img_bytes = await image.read()
        img = Image.open(io.BytesIO(img_bytes)).convert("RGB")
        x = transform(img).unsqueeze(0).to(
            DEVICE,
            dtype=torch.float16 if DEVICE == "cuda" else torch.float32
        )

        # ------------------------- 1) Router (taxonomía) ------------------------
        t0 = time.time()
        with torch.no_grad():
            router_logits = router_model(x)
            router_probs = torch.softmax(router_logits, dim=1)[0]
        router_ms = round((time.time() - t0) * 1000, 2)

        top_idx = int(torch.argmax(router_probs))
        top_class = CLASSES[top_idx]
        top_conf = round(float(router_probs[top_idx]), 4)

        result = {
            "taxon_predicted": top_class,
            "taxon_confidence": top_conf,
            "inference_router_ms": router_ms,
            "expert_invoked": False,
            "device": DEVICE,
            # Referencias, para que el controlador decida qué es "concluyente"
            "router_min_conf": THRESHOLDS["router_min_conf"],
            "expert_min_conf": THRESHOLDS["expert_min_conf"],
        }

        # ------------------------- 2) Experto (especie) -------------------------
        # La decisión de si esto es "válido" o "no concluyente"
        # NO se toma aquí. Siempre que exista experto, se invoca.
        expert_model, expert_labels, expert_file = load_expert_model(top_class)
        if expert_model is not None:
            t1 = time.time()
            with torch.no_grad():
                expert_logits = expert_model(x)
                expert_probs = torch.softmax(expert_logits, dim=1)[0]
            expert_ms = round((time.time() - t1) * 1000, 2)

            # Top-1 y Top-k (por defecto k=3)
            sorted_probs, sorted_indices = torch.sort(expert_probs, dim=0, descending=True)
            top1_idx = int(sorted_indices[0])
            top1_label = expert_labels[top1_idx]
            top1_conf = float(sorted_probs[0])

            k = min(3, len(expert_labels))
            topk = []
            for rank in range(k):
                idx = int(sorted_indices[rank])
                topk.append({
                    "label": expert_labels[idx],
                    "confidence": round(float(sorted_probs[rank]), 4)
                })

            result.update({
                "expert_invoked": True,
                "expert_model": expert_file,
                "inference_expert_ms": expert_ms,
                # Compatibilidad hacia atrás
                "species_predicted": top1_label,
                "species_confidence": round(top1_conf, 4),
                # Nuevos campos más expresivos
                "species_top1": top1_label,
                "species_top1_confidence": round(top1_conf, 4),
                "species_topk": topk,
            })

        return result

    except Exception as e:
        return JSONResponse({"error": str(e)}, status_code=500)

    finally:
        try:
            # Limpieza de memoria
            del img
            if x is not None:
                del x
            if router_logits is not None:
                del router_logits
            if router_probs is not None:
                del router_probs
            if expert_logits is not None:
                del expert_logits
            if expert_probs is not None:
                del expert_probs
            if expert_model is not None and expert_model not in EXPERT_CACHE.values():
                del expert_model

            if DEVICE == "cuda":
                torch.cuda.empty_cache()
            gc.collect()
        except Exception as cleanup_error:
            print(f"⚠️ Limpieza de memoria falló: {cleanup_error}")
