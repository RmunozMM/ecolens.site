# CMS V4
CMS V4
CMS V4 es la cuarta versión del sistema de gestión de contenidos desarrollado por Rogelio Muñoz. Esta versión refuerza la modularidad, trazabilidad y escalabilidad para proyectos web profesionales y académicos.
Construido principalmente sobre Yii2, incluye administración avanzada, gestión multimedia, sistema de widgets y API para integración con frontends modernos como Vue.js.

🚀 Características Principales
Arquitectura modular: Separación clara por módulos, widgets, helpers y controladores.
Soporte para API REST: Integración y entrega de datos para consumo externo (frontends desacoplados, aplicaciones móviles, etc.).
Sistema de auditoría estandarizado: Seguimiento de cambios, usuarios y fechas en todas las entidades principales.
Gestión avanzada de contenidos: Incluye galería multimedia, artículos, servicios, clientes y páginas dinámicas.
Editor enriquecido: Integración de TinyMCE con subida y gestión de imágenes.
Accesibilidad y diseño responsivo: Interfaz optimizada para PC, con mejoras progresivas en accesibilidad WCAG.
Estructura de carpetas profesional y documentada.
📁 Estructura de Carpetas
panel-admin/ – Backend principal de administración y lógica CMS.
recursos/ – Recursos estáticos, imágenes, assets y uploads (excluye temporales y runtime).
docs/ – Documentación técnica y estructura del proyecto (docs/estructura_panel_admin.txt).
install/ – Scripts y utilidades para instalación o migraciones (opcional).
index.php – Entrada principal del sistema (ajustar según despliegue).
README.md – Documentación introductoria del proyecto.
⚙️ Instalación y Configuración
Clonar el repositorio
git clone https://github.com/RmunozMM/CMS_V4.git
cd CMS_V4
Instalar dependencias
Asegúrate de tener Composer y las extensiones PHP necesarias (para Yii2).

composer install
Configuración inicial
Copia el archivo de ejemplo .env.example (si existe) a .env y ajusta las variables según tu entorno local.
Ajusta la base de datos en config/db.php u otro archivo según tu configuración.
Configura los permisos de las carpetas runtime/ y web/assets/ si corresponde.
Migraciones de base de datos
php yii migrate
Servidor local
php yii serve
O usa MAMP/XAMPP según tu flujo.

💾 .gitignore recomendado
Asegúrate de excluir archivos temporales y carpetas generadas automáticamente:

/panel-admin/runtime/
/panel-admin/vendor/
/panel-admin/web/assets/
/recursos/uploads/
/recursos/tmp/
.env
.DS_Store
*.log
node_modules/
🛠 Tecnologías y Frameworks
Yii2 Framework
PHP 8.x
Composer
MySQL/MariaDB
JavaScript (integración TinyMCE, widgets propios)
HTML5/CSS3 (diseño responsive)
📚 Documentación Técnica
docs/estructura_panel_admin.txt – Estructura completa de la carpeta principal del CMS.
sitio_utilidades.php – Utilidades y funciones comunes (ver carpeta helpers).
Otros documentos y scripts en /docs/.
🤝 Contribuciones
¿Te interesa colaborar o proponer mejoras?

Haz un fork del proyecto.
Crea una rama para tu feature o fix.
Envía un Pull Request con una descripción clara.
Revisar guidelines internos antes de contribuir.

👤 Autor
Rogelio Muñoz
Ingeniero en Informática | Magíster en Ingeniería Informática (c)
Consultor en Transformación Digital y CRM | Arquitecto de Soluciones
rogeliomunoz.cl
Contacto: [rmunoz1612@gmail.com]
⚖️ Licencia
Privado

📝 Notas finales
El desarrollo y documentación de CMS V4 están en evolución constante.
Para detalles específicos de módulos o integración con frontends modernos (Vue, React), ver documentación en /docs/.
Para soporte o dudas, contactar al autor principal.