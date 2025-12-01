import requests
from bs4 import BeautifulSoup
import json
import os

# Obtener la ruta del directorio donde está el script
script_dir = os.path.dirname(os.path.abspath(__file__))

# URL de Bootstrap Icons
url = "https://icons.getbootstrap.com/"

# Obtener el HTML de la página
response = requests.get(url)
if response.status_code != 200:
    raise Exception(f"Error al obtener la página: {response.status_code}")

# Parsear el HTML con BeautifulSoup
soup = BeautifulSoup(response.text, "html.parser")

# Encontrar todos los elementos <li> que contienen los íconos
icon_elements = soup.find_all("li", class_="col mb-4")

# Extraer las clases de los iconos
icon_list = []
for icon in icon_elements:
    icon_tag = icon.find("i", class_="bi")  # Buscar el <i> con la clase "bi"
    if icon_tag:
        icon_class = icon_tag.get("class")[1]  # La segunda clase es el nombre del icono (ej: "bi-123")
        icon_list.append(icon_class)

# Definir la ruta completa del archivo JSON en la misma carpeta del script
json_path = os.path.join(script_dir, "bootstrap_icons.json")

# Guardar en un archivo JSON
with open(json_path, "w", encoding="utf-8") as f:
    json.dump(icon_list, f, indent=4, ensure_ascii=False)

print(f"✅ Archivo 'bootstrap_icons.json' generado correctamente en {json_path}")