# EcoLens · Plataforma de reconocimiento y visualización de fauna silvestre

EcoLens es un sistema de reconocimiento y visualización de fauna silvestre orientado a parques nacionales y entornos naturales. Combina un motor de visión por computador (router + modelos expertos) con un CMS web completo para gestionar taxonomías, especies, detecciones, observadores y contenido editorial.

Este repositorio corresponde al **sitio completo de EcoLens**:

- **Panel de administración (CMS)** para gestionar contenidos, catálogo de fauna, taxonomías y usuarios.
- **Portal público** para consulta de especies, visualización de detecciones y exploración del ecosistema EcoLens.
- **Capa de APIs REST** para integrar el CMS con frontends modernos y servicios externos.
- **Integración con modelos de IA** desplegados vía FastAPI (código de la API incluido; pesos de los modelos excluidos del repositorio).

La plataforma está construida sobre el motor **CMS V4/V5** desarrollado por Rogelio Muñoz, adaptado y especializado para el caso de uso de EcoLens.

---

## 🚀 Características principales

- **Detección de fauna con IA**  
  Integración con un backend de visión por computador (FastAPI + PyTorch) basado en un **router general** y **modelos expertos por grupo taxonómico**.

- **Panel de administración (panel-admin/)**  
  Backend construido en **Yii2** con:

  - Gestión completa de contenidos (artículos, servicios, páginas, testimonios, clientes, proyectos, etc.).
  - Módulos específicos para **Taxonomías** y **Especies**.
  - Registro y revisión de **detecciones** y **observadores**.
  - Widgets personalizados (actividad reciente, exportadores, editor TinyMCE extendido, accesibilidad, etc.).

- **Portal público (sitio/)**  
  Sitio web desacoplado que consume el contenido vía API del CMS:

  - Portada EcoLens con contenido dinámico.
  - Exploración de especies y taxonomías.
  - Visualización de detecciones y detalle por especie.
  - Flujos de registro, login y perfil de observador.

- **API REST (módulo api del CMS)**  
  Endpoints normalizados para:

  - Contenido del sitio (perfil, artículos, servicios, páginas, etc.).
  - Operaciones de observadores y detecciones.
  - Integración con el frontend y con el backend de IA.

- **Sistema de auditoría estandarizado**  
  Manejo consistente de `created_at`, `updated_at`, `created_by`, `updated_by` en las entidades principales.

- **Gestión avanzada de contenidos**  
  Galería multimedia, manejo de imágenes por entidad, plantillas de layout, menús dinámicos y bloques reutilizables.

- **Editor enriquecido**  
  Integración de **TinyMCE** personalizada con:

  - Subida de imágenes al repositorio interno.
  - Exploración de la galería desde el editor.
  - Herramientas avanzadas de edición y recorte.

- **Accesibilidad y diseño responsivo**  
  Panel de administración optimizado para escritorio, con progresivas mejoras alineadas a criterios **WCAG**.

---

## 📁 Estructura principal de carpetas

- `install/`  
  Asistente y scripts de instalación del CMS y del sitio.

- `panel-admin/`  
  Backend principal de administración (Yii2): controladores, modelos, vistas, widgets, módulos (`api`), assets y configuración.

- `sitio/`  
  Frontend público de EcoLens (Yii2) que consume la API del CMS y renderiza el sitio para usuarios finales.

- `apis/modelo_router_api/`  
  Código de la API FastAPI para el router y modelos expertos de reconocimiento de fauna.

  > **Nota:** Los pesos de los modelos y archivos pesados se gestionan fuera del repositorio.

- `recursos/`  
  Recursos estáticos, imágenes y archivos de soporte. Contiene, entre otros, el script SQL `CMS_V5_FINAL.sql` con la estructura base del CMS.

- `template/`  
  Plantilla estática de referencia utilizada para el diseño del sitio EcoLens.

- `index.php`  
  Punto de entrada principal a nivel raíz (según configuración del servidor web).

- `README.md`  
  Este documento.

---

## ⚙️ Instalación y configuración (visión general)

### 1. Requisitos

- **PHP** 8.x (recomendado) con extensiones compatibles con Yii2.
- **MySQL / MariaDB** para la base de datos del CMS y del sitio.
- **Composer** para la gestión de dependencias PHP.
- **Python 3.x** (opcional, para levantar la API de modelos de IA).
- Servidor local o entorno compatible (MAMP, XAMPP, contenedores, etc.).

### 2. Clonar el repositorio

```bash
git clone https://github.com/RmunozMM/ecolens.site.git
cd ecolens.site
```

### 3. Backend CMS (panel-admin)

1. Ingresar a la carpeta `panel-admin/`.
2. Instalar dependencias vía Composer (o utilizar `vendor` según el flujo del entorno):

   ```bash
   composer install
   ```

3. Configurar la conexión a base de datos en los archivos de configuración correspondientes (por ejemplo, utilizando `recursos/CMS_V5_FINAL.sql` como base de estructura).
4. Ajustar `config/web.php` y parámetros en `config/params.php` según el entorno.

### 4. Frontend del sitio (sitio/)

1. Ingresar a la carpeta `sitio/`.
2. Ajustar configuración de entorno en `config/ecolens_env.php` y parámetros en `config/params.php`.
3. Verificar las rutas hacia la API del CMS y los recursos (`recursos/`).

### 5. API de modelos (opcional para entorno local)

En `apis/modelo_router_api/` se encuentra el código base de la API de modelos de IA. El despliegue típico considera:

1. Crear y activar un entorno virtual de Python.
2. Instalar dependencias:

   ```bash
   pip install -r requirements.txt
   ```

3. Configurar rutas a los pesos de los modelos (gestionados fuera del repositorio).
4. Levantar el servicio FastAPI con Uvicorn u otro servidor ASGI.

> La configuración exacta del entorno de IA forma parte de la documentación técnica de la tesis y de los anexos asociados.

---

## 💾 .gitignore recomendado

Para entornos derivados o instalaciones nuevas, se sugiere excluir del control de versiones:

```gitignore
/panel-admin/runtime/
/panel-admin/vendor/
/panel-admin/web/assets/
/sitio/runtime/
/sitio/vendor/
/sitio/web/assets/
/recursos/uploads/
/recursos/tmp/
/apis/modelo_router_api/models/
/apis/modelo_router_api/models_experts/
/apis/modelo_router_api/*.zip
*.pth
*.pt
*.onnx
.env
.DS_Store
*.log
node_modules/
```

---

## 🛠 Tecnologías y frameworks

- **Backend CMS y sitio**: Yii2 Framework (PHP)
- **Lenguaje backend**: PHP 8.x
- **Base de datos**: MySQL / MariaDB
- **Gestor de dependencias**: Composer
- **IA y visión por computador**: Python, FastAPI, PyTorch (router + modelos expertos)
- **Frontend**: HTML5, CSS3, JavaScript, integración con TinyMCE y widgets propios

---

## 📚 Documentación técnica

La documentación detallada del proyecto se encuentra distribuida entre:

- Archivos de configuración y helpers del CMS (`panel-admin/helpers/`, `recursos/CMS_V5_FINAL.sql`).
- Código del módulo API (`panel-admin/modules/api/`).
- Código de la API de modelos (`apis/modelo_router_api/`).
- Anexos y documentos asociados a la tesis de Magíster en Ingeniería Informática, donde se describe el diseño de:
  - Arquitectura router + modelos expertos.
  - Flujos de detección, validación y registro de fauna.
  - Estrategia de despliegue y niveles de madurez tecnológica (TRL).

---

## 👤 Autor

**Rogelio Muñoz Muñoz**  
Ingeniero en Informática | Magíster en Ingeniería Informática (c)  
Consultor en Transformación Digital y CRM | Arquitecto de Soluciones

Sitio personal: **rogeliomunoz.cl**  
Contacto: **rmunoz1612@gmail.com**

**Valeria Soriano**
Ingeniero Civil Industrial

Contacto: **vsorianof@gmail.com**

---

## ⚖️ Licencia

Este proyecto se encuentra bajo **licencia privada**.  
No está autorizado su uso, distribución o modificación pública sin consentimiento explícito del autor.

---

## 📝 Notas finales

EcoLens nace como parte de un trabajo de investigación aplicada orientado a la conservación de la biodiversidad, utilizando visión por computador y arquitecturas web modernas para acercar la información de fauna silvestre a personas, instituciones y comunidades.

Este repositorio corresponde a la implementación del sitio y del CMS que acompañan a los modelos de IA. Para dudas, mejoras o soporte en contextos académicos o institucionales, contactar directamente al autor.
