-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 04-12-2025 a las 12:07:29
-- Versión del servidor: 10.11.14-MariaDB-ubu2404
-- Versión de PHP: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ecol_ecolens_bd`
--

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `actividad_reciente`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `actividad_reciente` (
`tabla` varchar(9)
,`id` int(10) unsigned
,`updated_by` int(10) unsigned
,`updated_at` datetime /* mariadb-5.3 */
,`nombre_registro` varchar(255)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos`
--

CREATE TABLE `articulos` (
  `art_id` int(10) UNSIGNED NOT NULL COMMENT 'ID único del artículo',
  `art_titulo` varchar(255) NOT NULL COMMENT 'Título del artículo',
  `art_contenido` longtext DEFAULT NULL COMMENT 'Contenido HTML del artículo',
  `art_resumen` text DEFAULT NULL COMMENT 'Resumen o descripción del artículo',
  `art_etiquetas` varchar(255) DEFAULT NULL COMMENT 'Palabras clave o etiquetas del artículo',
  `art_fecha_publicacion` datetime DEFAULT NULL,
  `art_destacado` enum('SI','NO') DEFAULT 'NO' COMMENT 'Indicador para marcar el artículo como destacado',
  `art_vistas` int(10) UNSIGNED DEFAULT 0 COMMENT 'Número de veces que se ha visto el artículo',
  `art_likes` int(10) UNSIGNED DEFAULT 0 COMMENT 'Número de "me gusta" del artículo',
  `art_comentarios_habilitados` enum('SI','NO') DEFAULT 'SI' COMMENT 'Indicador para permitir comentarios en el artículo',
  `art_palabras_clave` text DEFAULT NULL COMMENT 'Palabras clave relacionadas con el contenido del artículo',
  `art_meta_descripcion` text DEFAULT NULL COMMENT 'Meta descripción del artículo',
  `art_slug` varchar(255) DEFAULT NULL COMMENT 'Slug del artículo (único)',
  `art_estado` enum('borrador','publicado') NOT NULL DEFAULT 'borrador' COMMENT 'Estado del artículo',
  `art_categoria_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID de la categoría del artículo',
  `art_notificacion` enum('SI','NO') DEFAULT 'SI' COMMENT '¿Notificar a suscriptores?',
  `art_imagen` varchar(255) DEFAULT NULL COMMENT 'URL de la imagen principal del artículo',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabla para almacenar los artículos del sitio web.';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asuntos`
--

CREATE TABLE `asuntos` (
  `asu_id` int(10) UNSIGNED NOT NULL COMMENT 'ID del asunto',
  `asu_nombre` varchar(255) NOT NULL COMMENT 'Nombre del asunto',
  `asu_publicado` enum('SI','NO') NOT NULL COMMENT 'Indicador de si el asunto está publicado',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabla que almacena los asuntos de correo';

--
-- Volcado de datos para la tabla `asuntos`
--

INSERT INTO `asuntos` (`asu_id`, `asu_nombre`, `asu_publicado`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'Consulta', 'SI', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(2, 'Cotización', 'SI', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(3, 'Reclamo', 'SI', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(4, 'Contacto', 'SI', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(5, 'Otro', 'SI', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_opciones`
--

CREATE TABLE `categorias_opciones` (
  `cat_id` int(10) UNSIGNED NOT NULL COMMENT 'Identificador único de la categoría de opción',
  `cat_nombre` varchar(50) NOT NULL COMMENT 'Nombre único de la categoría (ej: visual, sistema, api)',
  `cat_descripcion` varchar(255) DEFAULT NULL COMMENT 'Descripción breve del propósito de la categoría',
  `cat_icono` varchar(50) DEFAULT NULL COMMENT 'Clase CSS o nombre de ícono para UI (opcional)',
  `cat_orden` int(10) UNSIGNED DEFAULT 1 COMMENT 'Orden de visualización en el panel',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha de creación del registro',
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Fecha de última modificación',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó la categoría',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que modificó por última vez la categoría'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Categorías para agrupar opciones del sistema';

--
-- Volcado de datos para la tabla `categorias_opciones`
--

INSERT INTO `categorias_opciones` (`cat_id`, `cat_nombre`, `cat_descripcion`, `cat_icono`, `cat_orden`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'programador', 'Opciones avanzadas y parámetros internos de programación', 'fa fa-code', 1, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(2, 'meta', 'Configuración meta o general de módulos y sistema', 'fa fa-cogs', 2, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(3, 'sitio', 'Parámetros y textos públicos del sitio web', 'fa fa-globe', 3, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(4, 'visual', 'Colores, estilos y branding visual del sitio', 'fa fa-paint-brush', 4, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(5, 'api', 'Configuración de acceso a APIs y servicios externos', 'fa fa-plug', 5, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(6, 'correo', 'Opciones de envío y recepción de correos electrónicos', 'fa fa-envelope', 6, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(7, 'usuarios', 'Opciones para administración de usuarios y permisos', 'fa fa-users', 7, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(8, 'seguridad', 'Opciones de seguridad, claves y validaciones', 'fa fa-shield', 8, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(9, 'integracion', 'Parámetros para integración con sistemas externos', 'fa fa-exchange-alt', 9, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(10, 'mantenimiento', 'Opciones para gestión y mensajes de mantenimiento', 'fa fa-tools', 10, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_articulo`
--

CREATE TABLE `categoria_articulo` (
  `caa_id` int(10) UNSIGNED NOT NULL COMMENT 'ID único de la categoría',
  `caa_nombre` varchar(255) NOT NULL COMMENT 'Nombre de la categoría',
  `caa_slug` varchar(255) NOT NULL COMMENT 'Slug de la categoría',
  `caa_estado` enum('publicado','borrador') NOT NULL DEFAULT 'borrador' COMMENT 'Estado de la categoría (publicado/borrador)',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabla para almacenar las categorías de artículos.';

--
-- Volcado de datos para la tabla `categoria_articulo`
--

INSERT INTO `categoria_articulo` (`caa_id`, `caa_nombre`, `caa_slug`, `caa_estado`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'Artículo de Opinión', 'artículo-de-opinion', 'publicado', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(2, 'Noticias', 'noticias', 'publicado', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(3, 'Tecnología', 'tecnologia', 'publicado', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(4, 'Viajes', 'viajes', 'publicado', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(5, 'Salud y Bienestar', 'salud-y-bienestar', 'publicado', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(6, 'Cultura', 'cultura', 'publicado', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(7, 'Entretenimiento', 'entretenimiento', 'publicado', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_servicios`
--

CREATE TABLE `categoria_servicios` (
  `cas_id` int(10) UNSIGNED NOT NULL COMMENT 'ID de la categoría de servicios',
  `cas_nombre` varchar(255) NOT NULL COMMENT 'Nombre de la categoría de servicios',
  `cas_publicada` enum('SI','NO') NOT NULL COMMENT 'Estado de publicación de la categoría de servicios',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categoria_servicios`
--

INSERT INTO `categoria_servicios` (`cas_id`, `cas_nombre`, `cas_publicada`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'Diseño y Desarrollo Web', 'SI', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(2, 'Marketing Digital', 'SI', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(3, 'Aplicaciones y Software', 'SI', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(4, 'Servicios de Consultoría', 'SI', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(5, 'Infraestructura y Redes', 'SI', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `cli_id` int(10) UNSIGNED NOT NULL COMMENT 'ID del Cliente',
  `cli_nombre` varchar(255) NOT NULL COMMENT 'Nombre del Cliente',
  `cli_email` varchar(255) NOT NULL COMMENT 'Correo Electrónico del Cliente',
  `cli_telefono` varchar(20) DEFAULT NULL COMMENT 'Teléfono del Cliente',
  `cli_direccion` varchar(255) DEFAULT NULL COMMENT 'Dirección del Cliente',
  `cli_estado` enum('SI','NO') NOT NULL DEFAULT 'SI' COMMENT 'Estado del Cliente (SI/NO)',
  `cli_logo` varchar(255) DEFAULT NULL COMMENT 'Ruta del Logotipo del Cliente',
  `cli_publicado` enum('SI','NO') NOT NULL DEFAULT 'SI' COMMENT 'Publicado (SI/NO)',
  `cli_destacado` enum('SI','NO') NOT NULL DEFAULT 'NO' COMMENT 'Destacado (SI/NO)',
  `cli_slug` varchar(255) NOT NULL COMMENT 'Slug del cliente ',
  `cli_descripcion` text DEFAULT NULL COMMENT 'Descripción del Cliente',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabla de Clientes';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colores`
--

CREATE TABLE `colores` (
  `col_id` int(10) UNSIGNED NOT NULL,
  `col_nombre` varchar(255) NOT NULL,
  `col_valor` mediumtext DEFAULT NULL,
  `col_descripcion` mediumtext DEFAULT NULL,
  `col_layout_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del tema',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `correos_electronicos`
--

CREATE TABLE `correos_electronicos` (
  `cor_id` int(10) UNSIGNED NOT NULL COMMENT 'ID del correo electrónico',
  `cor_nombre` varchar(255) NOT NULL COMMENT 'Nombre del remitente',
  `cor_correo` varchar(255) NOT NULL COMMENT 'Correo electrónico del remitente',
  `cor_asunto` varchar(255) NOT NULL COMMENT 'Asunto del correo',
  `cor_mensaje` mediumtext NOT NULL COMMENT 'Cuerpo del correo electrónico',
  `cor_fecha_consulta` datetime DEFAULT NULL COMMENT 'Fecha de consulta del correo electrónico',
  `cor_fecha_respuesta` datetime DEFAULT NULL COMMENT 'Fecha de respuesta del correo electrónico',
  `cor_estado` enum('pendiente','resuelto') NOT NULL DEFAULT 'pendiente' COMMENT 'Estado del correo electrónico',
  `cor_respuesta` mediumtext DEFAULT NULL COMMENT 'Campo para almacenar la respuesta al correo electrónico',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `correos_electronicos`
--

INSERT INTO `correos_electronicos` (`cor_id`, `cor_nombre`, `cor_correo`, `cor_asunto`, `cor_mensaje`, `cor_fecha_consulta`, `cor_fecha_respuesta`, `cor_estado`, `cor_respuesta`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(2, 'Test', 'rogeliomunozmunoz@outlook.com', 'Consulta', 'Prueba de mensaje', '2025-10-27 23:10:08', '2025-10-27 21:00:20', 'resuelto', '<p>Hola, estoy respondiendo tu mensake..&nbsp;</p>', '2025-10-27 23:10:08', '2025-10-27 21:00:20', NULL, 1),
(3, 'Test', 'rogeliomunozmunoz@outlook.com', 'Consulta', 'Prueba de mensaje', '2025-10-27 23:15:38', NULL, 'pendiente', NULL, '2025-10-27 23:15:38', '2025-10-27 23:15:38', NULL, NULL),
(4, 'Test', 'rogeliomunozmunoz@outlook.com', 'Reclamo', 'Mensaje 2.. ', '2025-10-27 23:22:26', NULL, 'pendiente', NULL, '2025-10-27 23:22:26', '2025-10-27 23:22:26', NULL, NULL),
(5, 'Test', 'rogeliomunozmunoz@outlook.com', 'Consulta', 'Conuslta 3', '2025-10-27 23:25:18', NULL, 'pendiente', NULL, '2025-10-27 23:25:18', '2025-10-27 23:25:18', NULL, NULL),
(6, 'valeria paz soriano', 'valeriapaz.sf@gmail.com', 'Cotización', 'Holis', '2025-10-27 23:28:42', '2025-10-28 21:50:50', 'resuelto', '<p>Hola</p>', '2025-10-27 23:28:42', '2025-10-28 21:50:50', NULL, 5),
(7, 'Test', 'rogeliomunozmunoz@outlook.com', 'Contacto', 'asdasdas', '2025-10-27 20:33:41', NULL, 'pendiente', NULL, '2025-10-27 20:33:41', '2025-10-27 20:33:41', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curriculum`
--

CREATE TABLE `curriculum` (
  `cur_id` int(10) UNSIGNED NOT NULL COMMENT 'Llave primaria del curriculum',
  `cur_per_id` int(10) UNSIGNED NOT NULL COMMENT 'Referencia al perfil (per_id)',
  `cur_titulo` varchar(255) NOT NULL COMMENT 'Título del Curriculum (ej. "Currículum Vitae")',
  `cur_subtitulo` varchar(255) DEFAULT NULL COMMENT 'Subtítulo o tagline opcional',
  `cur_casa_estudio` varchar(255) NOT NULL COMMENT 'Casa de estudios indicada en el curriculum',
  `cur_resumen_profesional` mediumtext DEFAULT NULL COMMENT 'Resumen profesional extendido para el CV',
  `cur_estilos` text DEFAULT NULL COMMENT 'Configuración adicional (por ejemplo, JSON o CSS para personalizar el CV)',
  `cur_contenido` mediumtext DEFAULT NULL COMMENT 'Contenido o plantilla del CV',
  `singleton` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `curriculum`
--

INSERT INTO `curriculum` (`cur_id`, `cur_per_id`, `cur_titulo`, `cur_subtitulo`, `cur_casa_estudio`, `cur_resumen_profesional`, `cur_estilos`, `cur_contenido`, `singleton`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 1, '', NULL, '', NULL, '', NULL, 1, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `cur_id` int(10) UNSIGNED NOT NULL COMMENT 'ID único del curso',
  `cur_titulo` varchar(255) NOT NULL COMMENT 'Título del curso',
  `cur_descripcion` mediumtext DEFAULT NULL COMMENT 'Descripción general del curso',
  `cur_imagen` varchar(255) DEFAULT NULL COMMENT 'Imagen de portada del curso',
  `cur_estado` enum('borrador','publicado') NOT NULL DEFAULT 'borrador' COMMENT 'Estado del curso',
  `cur_slug` mediumtext NOT NULL COMMENT 'Slug del curso para URL amigable',
  `cur_icono` varchar(255) DEFAULT NULL COMMENT 'Ícono representativo del curso',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detecciones`
--

CREATE TABLE `detecciones` (
  `det_id` int(10) UNSIGNED NOT NULL COMMENT 'Identificador único de la detección',
  `det_imagen` varchar(255) DEFAULT NULL COMMENT 'Ruta o nombre del archivo procesado',
  `det_origen_archivo` varchar(255) DEFAULT NULL COMMENT 'Nombre original del archivo subido (si aplica)',
  `det_confianza_router` decimal(6,4) DEFAULT NULL COMMENT 'Confianza asignada por el modelo router (clasificación de taxón)',
  `det_confianza_experto` decimal(6,4) DEFAULT NULL COMMENT 'Confianza asignada por el modelo experto (clasificación de especie)',
  `det_modelo_router_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'Identificador del modelo router utilizado',
  `det_modelo_experto_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'Identificador del modelo experto utilizado (si aplica)',
  `det_tax_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'Identificador del taxón (ej. Mammalia) predicho por el router',
  `det_esp_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'Identificador de la especie predicha por el experto (si aplica)',
  `det_tiempo_router_ms` int(11) DEFAULT NULL COMMENT 'Duración del proceso de inferencia del modelo router (en milisegundos)',
  `det_tiempo_experto_ms` int(11) DEFAULT NULL COMMENT 'Duración del proceso de inferencia del modelo experto (en milisegundos)',
  `det_latitud` decimal(10,6) DEFAULT NULL COMMENT 'Latitud geográfica de la observación',
  `det_longitud` decimal(10,6) DEFAULT NULL COMMENT 'Longitud geográfica de la observación',
  `det_ubicacion_textual` varchar(255) DEFAULT NULL COMMENT 'Descripción textual del lugar de detección (reverse geocoding)',
  `det_obs_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'Identificador lógico del usuario que realizó la detección (puede ser NULL si es anónima)',
  `det_ip_cliente` varchar(45) DEFAULT NULL COMMENT 'Dirección IP del cliente (IPv4 o IPv6)',
  `det_dispositivo_tipo` enum('desktop','mobile','tablet','otros') DEFAULT 'otros' COMMENT 'Tipo de dispositivo desde donde se ejecutó la detección',
  `det_sistema_operativo` enum('Windows','macOS','Linux','Android','iOS','Otro') DEFAULT 'Otro' COMMENT 'Sistema operativo del dispositivo',
  `det_navegador` enum('Chrome','Firefox','Safari','Edge','Otro') DEFAULT 'Otro' COMMENT 'Navegador o entorno usado para ejecutar la detección',
  `det_fuente` enum('web','api','movil','sistema') NOT NULL DEFAULT 'web' COMMENT 'Fuente lógica desde la cual se originó la detección',
  `det_estado` enum('pendiente','validada','rechazada') NOT NULL DEFAULT 'pendiente' COMMENT 'Estado de revisión o validación de la detección',
  `det_revision_estado` enum('sin_revisar','en_revision','revisada') NOT NULL DEFAULT 'sin_revisar' COMMENT 'Estado de revisión manual (si aplica)',
  `det_feedback_usuario` enum('like','dislike') DEFAULT NULL COMMENT 'Feedback entregado por el observador para esta detección: like = coincide, dislike = no coincide',
  `det_feedback_fecha` datetime DEFAULT NULL COMMENT 'Fecha y hora en que el observador registró su feedback sobre la predicción',
  `det_fb_estado` enum('no_evaluada','confirmada','rechazada') NOT NULL DEFAULT 'no_evaluada' COMMENT 'Feedback del observador: ¿coincide la predicción?',
  `det_fb_tax_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'Clase sugerida por el observador (FK a taxonomias.tax_id)',
  `det_fb_comentario` varchar(255) DEFAULT NULL COMMENT 'Comentario textual del observador sobre la detección',
  `det_fb_fecha` datetime DEFAULT NULL COMMENT 'Fecha y hora en que el observador entregó feedback',
  `det_observaciones` text DEFAULT NULL COMMENT 'Notas internas o comentarios sobre la detección',
  `det_validado_por` int(10) UNSIGNED DEFAULT NULL COMMENT 'Usuario que validó manualmente la detección (si aplica)',
  `det_validacion_fecha` datetime DEFAULT NULL COMMENT 'Fecha en que la detección fue validada o revisada',
  `det_fecha` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha y hora exacta del proceso de detección (inferencia)',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha de creación del registro',
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Fecha de última modificación del registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Registro de detecciones automáticas generadas por los modelos IA de EcoLens. Cada fila corresponde a una inferencia (router + experto) con contexto de dispositivo, ubicación y usuario.';

--
-- Volcado de datos para la tabla `detecciones`
--

INSERT INTO `detecciones` (`det_id`, `det_imagen`, `det_origen_archivo`, `det_confianza_router`, `det_confianza_experto`, `det_modelo_router_id`, `det_modelo_experto_id`, `det_tax_id`, `det_esp_id`, `det_tiempo_router_ms`, `det_tiempo_experto_ms`, `det_latitud`, `det_longitud`, `det_ubicacion_textual`, `det_obs_id`, `det_ip_cliente`, `det_dispositivo_tipo`, `det_sistema_operativo`, `det_navegador`, `det_fuente`, `det_estado`, `det_revision_estado`, `det_feedback_usuario`, `det_feedback_fecha`, `det_fb_estado`, `det_fb_tax_id`, `det_fb_comentario`, `det_fb_fecha`, `det_observaciones`, `det_validado_por`, `det_validacion_fecha`, `det_fecha`, `created_at`, `updated_at`) VALUES
(1, 'detecciones/1.jpg', 'lince.jpg', 0.9970, 0.9871, NULL, 2, 6, 2, 1354, 1233, -33.469503, -70.585105, '4415, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '127.0.0.1', 'desktop', 'macOS', 'Firefox', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-19 21:26:52', '2025-10-19 21:26:52', '2025-10-19 21:26:52'),
(2, 'detecciones/2.jpg', 'coyote.jpg', 0.9962, 0.9995, NULL, 2, 6, 1, 1272, 1211, -33.469566, -70.585154, '4407, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '127.0.0.1', 'desktop', 'macOS', 'Firefox', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-19 21:28:06', '2025-10-19 21:28:06', '2025-10-19 21:28:06'),
(3, 'detecciones/3.jpeg', 'lince_rojo2.jpeg', 0.9846, 0.9993, NULL, 2, 6, 2, 683, 745, -33.469521, -70.585080, '4421, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 01:10:46', '2025-10-25 01:10:46', '2025-10-25 01:15:15'),
(13, 'detecciones/13.jpg', 'lince.jpg', 0.9970, 0.9871, NULL, 2, 6, 2, 766, 742, -33.469468, -70.585104, '4415, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', NULL, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 01:24:14', '2025-10-25 01:24:14', '2025-10-25 01:24:15'),
(14, 'detecciones/14.jpg', 'coyote.jpg', 0.9962, 0.9995, 5, 2, 6, 1, 682, 776, -33.469579, -70.584247, '4482, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', NULL, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 01:30:53', '2025-10-25 01:30:53', '2025-10-25 01:30:53'),
(15, 'detecciones/15.jpg', 'coyote.jpg', 0.9962, 0.9995, 5, 2, 6, 1, 674, 713, -33.469396, -70.585058, '4420, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', NULL, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 02:29:23', '2025-10-25 02:29:23', '2025-10-25 02:29:23'),
(16, 'detecciones/16.jpg', 'coyote.jpg', 0.9962, 0.9995, 5, 2, 6, 1, 692, 772, -33.469638, -70.585181, '1573, Peatones 28, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 02:34:44', '2025-10-25 02:34:44', '2025-10-25 02:34:44'),
(17, 'detecciones/17.jpg', 'coyote.jpg', 0.9962, 0.9995, 5, 2, 6, 1, 680, 715, -33.469471, -70.585082, '4419, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 02:41:14', '2025-10-25 02:41:14', '2025-10-25 02:41:15'),
(18, 'detecciones/18.jpeg', 'lince_rojo2.jpeg', 0.9846, 0.9993, 5, 2, 6, 2, 710, 775, -33.469206, -70.584824, '4412, Peatones 15, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 03:19:44', '2025-10-25 03:19:44', '2025-10-25 03:19:44'),
(19, 'detecciones/19.jpeg', '45B89D92-3629-4104-A206-26053938F8EA.jpeg', 0.9375, 0.4511, 5, 2, NULL, NULL, 782, 760, -33.469337, -70.584760, '4430, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', NULL, '104.28.115.49', 'mobile', 'Otro', 'Safari', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 03:23:11', '2025-10-25 03:23:11', '2025-10-25 03:23:12'),
(20, 'detecciones/20.jpeg', '45B89D92-3629-4104-A206-26053938F8EA.jpeg', 0.9375, 0.4511, 5, 2, NULL, NULL, 705, 692, -33.469334, -70.584761, '4430, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', NULL, '104.28.115.49', 'mobile', 'Otro', 'Safari', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 03:23:33', '2025-10-25 03:23:33', '2025-10-25 03:23:33'),
(21, 'detecciones/21.jpeg', 'IMG_8138.jpeg', 0.9965, 0.0000, 5, 6, NULL, NULL, 735, 0, -33.469331, -70.584813, '4424, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', NULL, '104.28.115.49', 'mobile', 'Otro', 'Safari', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 03:23:59', '2025-10-25 03:23:59', '2025-10-25 03:23:59'),
(22, 'detecciones/22.jpg', '1000082199.jpg', 0.5844, 0.0000, 5, 6, NULL, NULL, 684, 0, NULL, NULL, 'Ubicación no disponible', NULL, '186.189.103.125', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 11:33:36', '2025-10-25 11:33:36', '2025-10-25 11:33:36'),
(27, 'detecciones/27.jpg', 'image.jpg', 0.8609, 0.8991, 5, 2, NULL, NULL, 715, 1007, -33.469331, -70.584813, '4424, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', NULL, '190.114.38.97', 'mobile', 'Otro', 'Safari', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 18:57:36', '2025-10-25 18:57:36', '2025-10-25 18:57:36'),
(28, 'detecciones/28.jpg', 'image.jpg', 0.7532, 0.0000, 5, 6, NULL, NULL, 765, 0, -33.469331, -70.584813, '4424, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', NULL, '190.114.38.97', 'mobile', 'Otro', 'Safari', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 18:59:54', '2025-10-25 18:59:54', '2025-10-25 18:59:55'),
(29, 'detecciones/29.jpg', 'image.jpg', 0.7318, 0.0000, 5, 6, NULL, NULL, 870, 0, -33.469365, -70.584779, '4428, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', NULL, '190.114.38.97', 'mobile', 'Otro', 'Safari', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 19:00:57', '2025-10-25 19:00:57', '2025-10-25 19:00:57'),
(30, NULL, NULL, 0.7898, 0.0000, 5, 6, NULL, NULL, 926, 0, -33.469331, -70.584813, '4424, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', NULL, '190.114.38.97', 'mobile', 'Otro', 'Safari', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 19:08:11', '2025-10-25 19:08:11', '2025-10-25 19:08:11'),
(31, 'detecciones/31.jpg', 'image.jpg', 0.7898, 0.0000, 5, 6, NULL, NULL, 951, 0, -33.469331, -70.584813, '4424, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', NULL, '190.114.38.97', 'mobile', 'Otro', 'Safari', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 19:09:04', '2025-10-25 19:09:04', '2025-10-25 19:09:05'),
(32, 'detecciones/32.jpg', 'image.jpg', 0.7898, 0.0000, 5, 6, NULL, NULL, 860, 0, -33.469331, -70.584813, '4424, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', NULL, '190.114.38.97', 'mobile', 'Otro', 'Safari', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 19:14:49', '2025-10-25 19:14:49', '2025-10-25 19:14:50'),
(33, 'detecciones/33.jpg', '1000081017.jpg', 0.8562, 0.9349, 5, 2, 6, 2, 801, 894, -33.459421, -70.661975, 'Fantasilandia, 938, Avenida Beaucheff, Barrio Club Hípico, Santiago, Provincia de Santiago, Región Metropolitana de Santiago, 8370471, Chile', NULL, '186.189.103.125', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 19:51:23', '2025-10-25 19:51:23', '2025-10-25 19:51:24'),
(34, 'detecciones/34.jpg', 'lince.jpg', 0.9970, 0.9871, 5, 2, 6, 2, 718, 924, -33.469326, -70.585126, '4404, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 21:31:45', '2025-10-25 21:31:45', '2025-10-25 21:31:45'),
(36, 'detecciones/36.jpg', '1000070808.jpg', 0.9693, 0.9998, 5, 2, NULL, NULL, 693, 784, NULL, NULL, 'Ubicación no disponible', NULL, '201.188.152.7', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 21:36:10', '2025-10-25 21:36:10', '2025-10-25 21:36:11'),
(37, 'detecciones/37.jpg', '1000070808.jpg', 0.9693, 0.9998, 5, 2, NULL, NULL, 723, 878, NULL, NULL, 'Ubicación no disponible', NULL, '201.188.152.7', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 21:36:24', '2025-10-25 21:36:24', '2025-10-25 21:36:25'),
(38, 'detecciones/38.jpg', '1000070513.jpg', 0.7822, 0.0000, 5, 6, NULL, NULL, 707, 0, NULL, NULL, 'Ubicación no disponible', NULL, '201.188.152.7', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 21:36:42', '2025-10-25 21:36:42', '2025-10-25 21:36:42'),
(39, 'detecciones/39.jpeg', 'IMG_2050.jpeg', 0.9414, 0.8788, 5, 2, NULL, NULL, 710, 727, -33.469331, -70.584813, '4424, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'mobile', 'Otro', 'Safari', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 21:39:31', '2025-10-25 21:39:31', '2025-10-25 21:39:35'),
(40, 'detecciones/40.jpg', '1000070804.jpg', 0.9127, 0.9999, 5, 2, NULL, NULL, 855, 804, -33.468486, -70.616686, 'Los Jornaleros, Villa Rebeca Matte, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7780222, Chile', NULL, '201.188.152.7', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 21:41:09', '2025-10-25 21:41:09', '2025-10-25 21:41:10'),
(41, 'detecciones/41.jpg', '1000070804.jpg', 0.9127, 0.9999, 5, 2, NULL, NULL, 737, 737, NULL, NULL, 'Ubicación no disponible', NULL, '201.188.152.7', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 21:41:36', '2025-10-25 21:41:36', '2025-10-25 21:41:37'),
(42, 'detecciones/42.jpeg', 'IMG_8758.jpeg', 0.5373, 0.0000, 5, 6, NULL, NULL, 737, 0, -33.469331, -70.584813, '4424, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'mobile', 'Otro', 'Safari', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 21:48:21', '2025-10-25 21:48:21', '2025-10-25 21:48:22'),
(43, 'detecciones/43.png', 'Captura de pantalla 2025-10-23 a las 21.45.29.png', 0.2410, 0.0000, 5, 6, NULL, NULL, 652, 0, -33.469585, -70.585036, '1559, Peatones 28, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 21:48:43', '2025-10-25 21:48:43', '2025-10-25 21:48:43'),
(44, 'detecciones/44.jpg', 'coyote.jpg', 0.9962, 0.9995, 5, 2, 6, 1, 661, 876, -33.469418, -70.584990, '4422, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 21:54:58', '2025-10-25 21:54:58', '2025-10-25 21:54:58'),
(45, 'detecciones/45.jpeg', 'IMG_0240.jpeg', 0.9860, 0.0000, 5, 6, NULL, NULL, 802, 0, -33.469331, -70.584813, '4424, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'mobile', 'Otro', 'Safari', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 21:56:03', '2025-10-25 21:56:03', '2025-10-25 21:56:04'),
(46, 'detecciones/46.jpg', '1000010307.jpg', 0.9455, 0.7935, 5, 2, NULL, NULL, 691, 751, -33.470120, -70.616069, '1642, Pasaje Catorce, Villa Rebeca Matte, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7780222, Chile', NULL, '190.100.237.210', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 21:57:03', '2025-10-25 21:57:03', '2025-10-25 21:57:04'),
(47, 'detecciones/47.jpg', 'coyote.jpg', 0.9962, 0.9995, 5, 2, 6, 1, 866, 769, -33.469557, -70.585055, '1555, Peatones 28, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 22:26:40', '2025-10-25 22:26:40', '2025-10-25 22:26:40'),
(48, 'detecciones/48.jpg', '1000069610.jpg', 0.7089, 0.0000, 5, 6, NULL, NULL, 717, 0, NULL, NULL, 'Ubicación no disponible', NULL, '201.188.152.7', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 22:27:51', '2025-10-25 22:27:51', '2025-10-25 22:27:52'),
(49, 'detecciones/49.jpg', 'IMG-20251003-WA0024.jpg', 0.8050, 0.3748, 5, 2, NULL, NULL, 885, 742, -33.468505, -70.616692, '1461, Los Escultores, Villa Rebeca Matte, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7780222, Chile', NULL, '201.188.152.7', 'desktop', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 22:30:38', '2025-10-25 22:30:38', '2025-10-25 22:30:38'),
(50, 'detecciones/50.jpg', '1000010308.jpg', 0.9337, 0.5058, 5, 2, NULL, NULL, 694, 684, -33.470126, -70.616061, '1642, Pasaje Catorce, Villa Rebeca Matte, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7780222, Chile', NULL, '190.100.237.210', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 22:41:43', '2025-10-25 22:41:43', '2025-10-25 22:41:44'),
(51, 'detecciones/51.jpg', 'image.jpg', 0.9011, 0.4852, 5, 2, NULL, NULL, 659, 656, -33.471048, -70.584737, '1706, Calle 29, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', NULL, '181.42.167.39', 'mobile', 'Otro', 'Safari', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 22:51:38', '2025-10-25 22:51:38', '2025-10-25 22:51:39'),
(52, 'detecciones/52.jpg', '1000010307.jpg', 0.9455, 0.7935, 5, 2, NULL, NULL, 657, 648, -33.470119, -70.616074, '1642, Pasaje Catorce, Villa Rebeca Matte, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7780222, Chile', NULL, '190.100.237.210', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 22:57:57', '2025-10-25 22:57:57', '2025-10-25 22:57:58'),
(53, 'detecciones/53.jpg', 'coyote.jpg', 0.9962, 0.9995, 5, 2, 6, 1, 956, 990, -33.469409, -70.584959, '4422, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-26 04:12:46', '2025-10-26 04:12:46', '2025-10-26 04:12:46'),
(54, 'detecciones/54.jpeg', 'WhatsApp Image 2025-10-28 at 15.05.40 (1).jpeg', 0.4143, 0.0000, 5, 6, NULL, NULL, 1076, 0, NULL, NULL, 'Ubicación no disponible', 3, '45.239.211.38', 'desktop', 'Windows', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 21:58:21', '2025-10-28 21:58:21', '2025-10-28 21:58:21'),
(55, 'detecciones/55.jpg', 'IMG-1348.jpg', 0.7940, 0.0000, 5, 6, NULL, NULL, 807, 0, -33.640024, -70.352313, 'De La Inmaculada, San José de Maipo, Provincia de Cordillera, Región Metropolitana de Santiago, Chile', 3, '45.239.211.38', 'desktop', 'Windows', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 21:58:55', '2025-10-28 21:58:55', '2025-10-28 21:58:56'),
(56, 'detecciones/56.png', 'Gemini_Generated_Image_hjtns0hjtns0hjtn.png', 0.9974, 0.9972, 5, 2, 6, 2, 947, 1165, -33.640024, -70.352313, 'De La Inmaculada, San José de Maipo, Provincia de Cordillera, Región Metropolitana de Santiago, Chile', 3, '45.239.211.38', 'desktop', 'Windows', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 22:01:22', '2025-10-28 22:01:22', '2025-10-28 22:01:22'),
(57, 'detecciones/57.png', 'Gemini_Generated_Image_hjtns0hjtns0hjtn.png', 0.9974, 0.9972, 5, 2, 6, 2, 839, 823, -33.640024, -70.352313, 'De La Inmaculada, San José de Maipo, Provincia de Cordillera, Región Metropolitana de Santiago, Chile', 3, '45.239.211.38', 'desktop', 'Windows', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 22:01:54', '2025-10-28 22:01:54', '2025-10-28 22:01:54'),
(58, 'detecciones/58.jpg', '5b34eb4ad71c3.jpg', 0.9992, 0.9880, 5, 2, NULL, NULL, 713, 696, -33.640024, -70.352313, 'De La Inmaculada, San José de Maipo, Provincia de Cordillera, Región Metropolitana de Santiago, Chile', 3, '45.239.211.38', 'desktop', 'Windows', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 23:32:59', '2025-10-28 23:32:59', '2025-10-28 23:32:59'),
(59, 'detecciones/59.jpg', '1200px-Myocastor_coypus_-_ragondin.jpg', 0.8842, 0.5546, 5, 2, NULL, NULL, 873, 851, -33.594520, -70.379586, 'Los Queltehues, El Peumo Poniente, San José de Maipo, Provincia de Cordillera, Región Metropolitana de Santiago, Chile', 3, '45.239.211.38', 'desktop', 'Windows', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 23:42:54', '2025-10-28 23:42:54', '2025-10-28 23:42:55'),
(60, 'detecciones/60.jpg', 'lince.jpg', 0.9970, 0.9871, 5, 2, 6, 2, 897, 853, -33.469323, -70.585064, '4416, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 2, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', 'like', '2025-12-03 22:37:53', 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-29 09:22:50', '2025-10-29 09:22:50', '2025-12-03 22:37:53'),
(61, 'detecciones/61.jpg', 'coyote.jpg', 0.9962, 0.9995, 5, 2, 6, 1, 913, 1087, -33.469196, -70.585026, '4391, Calle 8, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 2, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', 'like', '2025-12-03 22:37:49', 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-29 09:32:32', '2025-10-29 09:32:33', '2025-12-03 22:37:49'),
(62, 'detecciones/62.jpg', 'coyote.jpg', 0.9962, 0.9995, 5, 2, 6, 1, 887, 826, -33.469445, -70.585254, '4389, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 2, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', 'like', '2025-12-03 22:37:50', 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-29 09:35:00', '2025-10-29 09:35:00', '2025-12-03 22:37:50'),
(63, 'detecciones/63.jpg', 'coyote.jpg', 0.9962, 0.9995, 5, 2, 6, 1, 801, 807, -33.469426, -70.585030, '4422, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 2, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', 'like', '2025-12-03 22:37:51', 'no_evaluada', NULL, NULL, NULL, NULL, 1, '2025-10-30 12:04:47', '2025-10-29 09:40:15', '2025-10-29 09:40:15', '2025-12-03 22:37:51'),
(64, 'detecciones/64.jpeg', 'IMG_4074.jpeg', 0.9977, 0.8671, 5, 2, 6, 2, 2571, 1743, -34.262539, -71.530926, 'La Estrella, Provincia de Cardenal Caro, Chile', 5, '168.196.203.112', 'mobile', 'Otro', 'Safari', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-31 14:07:12', '2025-10-31 14:07:12', '2025-10-31 14:07:13'),
(65, 'detecciones/65.jpeg', 'IMG_2449.jpeg', 0.9990, 0.0000, 5, 6, NULL, NULL, 878, 0, -33.345513, -70.669237, 'MAG Pedro Fontova, 7855, Avenida Pedro Fontova, Haras de Huechuraba, Huechuraba, Provincia de Santiago, Región Metropolitana de Santiago, 8600651, Chile', 1, '104.28.138.142', 'mobile', 'Otro', 'Safari', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-31 17:46:47', '2025-10-31 17:46:47', '2025-10-31 17:46:48'),
(66, 'detecciones/66.jpg', '1000086215.jpg', 0.9130, 0.7036, 5, 2, NULL, NULL, 864, 797, -33.594533, -70.379593, 'Los Queltehues, El Peumo Poniente, San José de Maipo, Provincia de Cordillera, Región Metropolitana de Santiago, Chile', 5, '45.239.211.18', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-02 22:22:26', '2025-11-02 22:22:26', '2025-11-02 22:22:27'),
(67, 'detecciones/67.jpg', '1000084746.jpg', 0.9672, 0.6949, 5, 2, NULL, NULL, 832, 772, -33.594533, -70.379593, 'Los Queltehues, El Peumo Poniente, San José de Maipo, Provincia de Cordillera, Región Metropolitana de Santiago, Chile', 5, '45.239.211.18', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-02 22:23:41', '2025-11-02 22:23:41', '2025-11-02 22:23:42'),
(68, 'detecciones/68.jpg', 'image.jpg', 0.5845, 0.0000, 5, 6, NULL, NULL, 1080, 0, -33.469335, -70.584817, '4424, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'mobile', 'Otro', 'Safari', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-03 11:14:05', '2025-11-03 11:14:05', '2025-11-03 11:14:07'),
(69, 'detecciones/69.jpg', 'coyote.jpg', 0.9962, 0.9995, 5, 2, NULL, NULL, 2536, 2531, -33.469228, -70.584984, '4397, Calle 8, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-11 17:25:36', '2025-11-11 17:25:36', '2025-11-11 17:25:37'),
(70, 'detecciones/70.jpg', 'coyote.jpg', 0.9962, 0.9995, 5, 2, 6, 1, 846, 921, -33.469284, -70.584966, '4422, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-11 17:29:36', '2025-11-11 17:29:36', '2025-11-11 17:29:36'),
(71, 'detecciones/71.jpg', 'coyote.jpg', 0.9962, 0.9995, 5, 2, NULL, NULL, 4286, 1763, -33.469404, -70.585083, '4416, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-11 20:21:10', '2025-11-11 20:21:10', '2025-11-11 20:21:10'),
(72, 'detecciones/72.jpeg', 'Procyon_lotor.jpeg', 0.9938, 1.0000, 5, 2, 6, 11, 841, 832, -33.469344, -70.585050, '4420, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-11 20:26:58', '2025-11-11 20:26:58', '2025-11-11 20:26:58'),
(73, 'detecciones/73.jpg', '1000058147.jpg', 0.6722, 0.0000, 5, 6, NULL, NULL, 911, 0, NULL, NULL, 'Ubicación no disponible', 6, '186.189.104.233', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-12 09:05:48', '2025-11-12 09:05:48', '2025-11-12 09:05:49'),
(74, 'detecciones/74.jpg', '1000058147.jpg', 0.6722, 0.0000, 5, 6, NULL, NULL, 820, 0, -33.532419, -70.794190, 'Laguna Arcoiris Oriente, Barrio Los Bosquinos, Maipú, Provincia de Santiago, Región Metropolitana de Santiago, 9253341, Chile', 6, '186.189.104.233', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-12 09:14:18', '2025-11-12 09:14:18', '2025-11-12 09:14:19'),
(75, NULL, NULL, NULL, NULL, 7, 8, NULL, NULL, NULL, NULL, -33.448900, -70.669300, 'Santiago Centro', 1, '54.86.50.139', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', 'like', '2025-12-03 22:04:14', 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-12 23:57:31', '2025-11-12 23:57:31', '2025-12-03 22:04:14'),
(76, 'detecciones/76.jpg', 'Lama_guanicoe.jpg', 0.9933, 0.9039, 5, 2, 6, 8, 963, 926, -33.469232, -70.585005, '4395, Calle 8, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', 'dislike', '2025-12-03 22:04:15', 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-14 16:50:21', '2025-11-14 16:50:21', '2025-12-03 22:04:15'),
(77, 'detecciones/77.jpg', 'Lama_guanicoe.jpg', 0.9933, 0.9039, 5, 2, 6, 8, 847, 918, -33.469205, -70.585080, '4385, Calle 8, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', 'dislike', '2025-12-03 22:04:16', 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-14 16:57:33', '2025-11-14 16:57:33', '2025-12-03 22:04:16'),
(78, 'detecciones/78.jpg', 'Lama_guanicoe.jpg', 0.9933, 0.9039, 5, 2, NULL, NULL, 870, 748, -33.469140, -70.584953, '4398, Peatones 15, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', 'dislike', '2025-12-03 22:04:20', 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-14 17:04:33', '2025-11-14 17:04:33', '2025-12-03 22:04:20'),
(79, 'detecciones/79.jpg', 'Lama_guanicoe.jpg', 0.9933, 0.9039, 5, 2, NULL, NULL, 1645, 854, -33.469375, -70.585143, '4402, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', 'dislike', '2025-12-03 22:04:19', 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-14 17:16:57', '2025-11-14 17:16:57', '2025-12-03 22:04:19'),
(80, 'detecciones/80.jpg', 'coyote.jpg', 0.9962, 0.9995, 5, 2, NULL, NULL, 723, 873, -33.469374, -70.585281, '4388, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', 'dislike', '2025-12-03 22:04:21', 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-14 17:17:57', '2025-11-14 17:17:57', '2025-12-03 22:04:21'),
(81, 'detecciones/81.jpg', '1000090143.jpg', 0.9904, 0.0000, 5, 6, 4, NULL, 810, 0, NULL, NULL, 'Ubicación no disponible', NULL, '45.232.94.247', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 13:21:20', '2025-11-15 13:21:20', '2025-11-15 13:21:21'),
(82, 'detecciones/82.jpg', '1000090143.jpg', 0.9904, 0.0000, 5, 6, 4, NULL, 908, 0, -22.909924, -68.202399, '191, Licancabur, Población Conde Duque, Condeduque, San Pedro de Atacama, Provincia de El Loa, Región de Antofagasta, 9660000, Chile', NULL, '45.232.94.247', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 13:22:11', '2025-11-15 13:22:11', '2025-11-15 13:22:11'),
(83, 'detecciones/83.jpg', '1000090143.jpg', 0.9904, 0.0000, 5, 6, 4, NULL, 797, 0, -22.909910, -68.202269, 'Vientos - la Yareta, 9, Calama, Población Conde Duque, Condeduque, San Pedro de Atacama, Provincia de El Loa, Región de Antofagasta, 9660000, Chile', NULL, '45.232.94.247', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 13:22:39', '2025-11-15 13:22:39', '2025-11-15 13:22:40'),
(84, 'detecciones/84.jpg', '1000089686.jpg', 0.9655, 0.9661, 5, 2, NULL, NULL, 692, 672, -22.909819, -68.202347, '191, Licancabur, Población Conde Duque, Condeduque, San Pedro de Atacama, Provincia de El Loa, Región de Antofagasta, 9660000, Chile', NULL, '45.232.94.247', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 13:23:01', '2025-11-15 13:23:01', '2025-11-15 13:23:02'),
(85, 'detecciones/85.jpg', 'Lama_guanicoe.jpg', 0.9933, 0.9039, 5, 2, NULL, NULL, 946, 975, -33.469167, -70.585061, '4387, Calle 8, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', 'dislike', '2025-12-03 22:04:26', 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-16 22:47:14', '2025-11-16 22:47:14', '2025-12-03 22:04:26'),
(86, 'detecciones/86.jpeg', 'Lama guanicoe.jpeg', 0.9869, 0.6694, 5, 2, NULL, NULL, 899, 904, -33.469319, -70.584905, '4424, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', 'dislike', '2025-12-03 22:04:24', 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-16 22:48:34', '2025-11-16 22:48:34', '2025-12-03 22:04:24'),
(87, 'detecciones/87.jpg', 'Capra hircus.jpg', 0.9984, 0.6076, 5, 2, NULL, NULL, 880, 877, -33.468837, -70.585553, '1524, Peatones 15, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', 'dislike', '2025-12-03 22:04:23', 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-16 22:49:48', '2025-11-16 22:49:48', '2025-12-03 22:04:23'),
(88, 'detecciones/88.webp', 'Myocastor coypus.webp', 0.9991, 0.8341, 5, 2, NULL, NULL, 1038, 964, -33.469419, -70.585355, '4375, Pasaje 23, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-16 22:51:08', '2025-11-16 22:51:08', '2025-11-16 22:51:08'),
(89, 'detecciones/89.jpg', 'coyote.jpg', 0.9962, 0.9995, 5, 2, NULL, NULL, 872, 817, -33.469176, -70.585069, '4387, Calle 8, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-16 23:14:17', '2025-11-16 23:14:17', '2025-11-16 23:14:17'),
(90, 'detecciones/90.jpg', 'Capra hircus.jpg', 0.9984, 0.5739, 5, 2, NULL, NULL, 1018, 748, -33.469034, -70.585157, '4377, Peatones 15, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-17 21:36:34', '2025-11-17 21:36:34', '2025-11-17 21:36:34'),
(91, 'detecciones/91.jpeg', 'Lama guanicoe.jpeg', 0.9869, 0.9887, 5, 2, 6, 4, 855, 671, -33.469033, -70.585158, '4377, Peatones 15, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', 'like', '2025-12-03 18:41:49', 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-17 21:36:54', '2025-11-17 21:36:54', '2025-12-03 18:41:49'),
(92, 'detecciones/92.webp', 'Equus ferus caballus.webp', 0.8366, 0.9969, 5, 2, NULL, NULL, 831, 690, -33.469189, -70.585165, '4377, Peatones 15, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', 'dislike', '2025-12-03 18:41:52', 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-17 21:37:33', '2025-11-17 21:37:33', '2025-12-03 18:41:52'),
(93, 'detecciones/93.jpeg', 'IMG_7217.jpeg', 0.9921, 0.8881, 5, 2, 6, 20, 712, 692, -33.469343, -70.584812, '4424, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'mobile', 'Otro', 'Safari', 'web', 'pendiente', 'sin_revisar', 'dislike', '2025-12-03 18:41:54', 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-17 21:58:56', '2025-11-17 21:58:56', '2025-12-03 18:41:54'),
(94, 'detecciones/94.webp', 'images.webp', 0.4589, 0.0000, 5, 6, NULL, NULL, 867, 0, NULL, NULL, 'Ubicación no disponible', 7, '181.42.205.16', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-17 22:03:47', '2025-11-17 22:03:47', '2025-11-17 22:03:47'),
(95, 'detecciones/95.webp', 'images.webp', 0.4589, 0.0000, 5, 6, NULL, NULL, 897, 0, -33.426022, -70.686668, 'Claudio Vicuña Morla, Barrio Tropezón, Quinta Normal, Provincia de Santiago, Región Metropolitana de Santiago, 8350302, Chile', 7, '181.42.205.16', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-17 22:03:55', '2025-11-17 22:03:55', '2025-11-17 22:03:55'),
(96, 'detecciones/96.webp', 'caballo.webp', 0.8953, 0.9981, 5, 2, NULL, NULL, 979, 795, -33.469256, -70.584964, '4399, Calle 8, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', 'like', '2025-12-03 18:41:48', 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-17 22:05:13', '2025-11-17 22:05:13', '2025-12-03 18:41:48'),
(97, 'detecciones/97.jpg', 'Leistes loyca.jpg', 0.9994, 0.9924, 5, 9, 4, 25, 953, 1032, -33.469174, -70.585139, '4379, Calle 8, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 2, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', 'like', '2025-12-03 22:37:47', 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-18 22:19:05', '2025-11-18 22:19:05', '2025-12-03 22:37:47'),
(98, 'detecciones/98.webp', 'caballo2.webp', 0.4589, 0.0000, 5, 6, NULL, NULL, 797, 0, -33.469033, -70.584841, '4408, Peatones 15, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 2, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', 'dislike', '2025-12-03 22:37:46', 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-18 22:21:52', '2025-11-18 22:21:52', '2025-12-03 22:37:46'),
(99, 'detecciones/99.webp', 'caballo2.webp', 0.4589, 0.0000, 5, 6, NULL, NULL, 846, 0, -33.469348, -70.585000, '4422, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 2, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', 'dislike', '2025-12-03 22:37:44', 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-18 22:39:17', '2025-11-18 22:39:18', '2025-12-03 22:37:44'),
(100, 'detecciones/100.webp', 'caballo2.webp', 0.4589, 0.9909, 5, 2, NULL, NULL, 1023, 1022, -33.469353, -70.585281, '4388, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 2, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', 'dislike', '2025-12-03 22:37:40', 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-18 22:49:52', '2025-11-18 22:49:52', '2025-12-03 22:37:40'),
(101, 'detecciones/101.jpg', 'Scelorchilus rubecula.jpg', 0.9993, 0.9926, 5, 9, 4, NULL, 889, 1011, -33.469378, -70.585006, '4422, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 2, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', 'dislike', '2025-12-03 22:37:42', 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-18 23:02:40', '2025-11-18 23:02:40', '2025-12-03 22:37:42'),
(102, 'detecciones/102.jpg', 'Sephanoides fernandensis.jpg', 0.9755, 0.9976, 5, 9, 4, 23, 862, 1061, -33.469450, -70.585229, '4389, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 2, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', 'like', '2025-12-03 22:37:41', 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-18 23:05:09', '2025-11-18 23:05:09', '2025-12-03 22:37:41'),
(103, 'detecciones/103.png', 'IMG_2719.png', 0.6318, 0.6065, 5, 2, NULL, NULL, 908, 879, -33.469343, -70.584812, '4424, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'mobile', 'Otro', 'Safari', 'web', 'pendiente', 'sin_revisar', 'like', '2025-12-03 18:41:47', 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-18 23:43:31', '2025-11-18 23:43:31', '2025-12-03 18:41:47'),
(104, 'detecciones/104.webp', 'caballo2.webp', 1.0000, 0.9909, 5, 2, 6, 30, 870, 833, -33.469475, -70.585169, '4401, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 2, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', 'like', '2025-12-03 22:37:37', 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-19 20:56:45', '2025-11-19 20:56:45', '2025-12-03 22:37:37'),
(105, 'detecciones/105.jpg', '1000044129.jpg', 0.9991, 0.8909, 5, 9, 4, 21, 870, 766, NULL, NULL, 'Ubicación no disponible', 8, '191.127.0.111', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-19 21:44:59', '2025-11-19 21:44:59', '2025-11-19 21:45:00'),
(106, 'detecciones/106.jpg', 'istockphoto-145245304-612x612.jpg', 1.0000, 0.9957, 5, 2, 6, 30, 840, 764, -33.594513, -70.379589, 'Los Queltehues, El Peumo Poniente, San José de Maipo, Provincia de Cordillera, Región Metropolitana de Santiago, Chile', 5, '45.239.211.18', 'desktop', 'Windows', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-19 22:42:50', '2025-11-19 22:42:50', '2025-11-19 22:42:50'),
(107, 'detecciones/107.jpg', 'Schopfkarakara.jpg', 1.0000, 0.9975, 5, 9, 4, 21, 845, 808, -33.587502, -70.407474, 'San José de Maipo, Provincia de Cordillera, Región Metropolitana de Santiago, Chile', 5, '45.239.211.18', 'desktop', 'Windows', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-19 22:45:08', '2025-11-19 22:45:08', '2025-11-19 22:45:08'),
(108, 'detecciones/108.jfif', 'descarga (1).jfif', 1.0000, 0.9796, 5, 2, 6, 4, 798, 729, -33.594509, -70.379597, 'Los Queltehues, El Peumo Poniente, San José de Maipo, Provincia de Cordillera, Región Metropolitana de Santiago, Chile', 5, '45.239.211.18', 'desktop', 'Windows', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-19 22:55:37', '2025-11-19 22:55:37', '2025-11-19 22:55:37'),
(109, 'detecciones/109.jpg', '1000092190.jpg', 1.0000, 0.9834, 5, 2, 6, 4, 868, 845, -33.594508, -70.379599, 'Los Queltehues, El Peumo Poniente, San José de Maipo, Provincia de Cordillera, Región Metropolitana de Santiago, Chile', 5, '45.239.211.18', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-19 23:00:16', '2025-11-19 23:00:16', '2025-11-19 23:00:16'),
(110, 'detecciones/110.jfif', 'descarga (1).jfif', 1.0000, 0.9796, 5, 2, 6, 4, 816, 804, -33.587502, -70.407474, 'San José de Maipo, Provincia de Cordillera, Región Metropolitana de Santiago, Chile', 5, '45.239.211.18', 'desktop', 'Windows', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-19 23:01:30', '2025-11-19 23:01:30', '2025-11-19 23:01:30'),
(111, 'detecciones/111.jpg', '1000092190.jpg', 1.0000, 0.9834, 5, 2, 6, 4, 840, 791, -33.594508, -70.379599, 'Los Queltehues, El Peumo Poniente, San José de Maipo, Provincia de Cordillera, Región Metropolitana de Santiago, Chile', 5, '45.239.211.18', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-19 23:02:19', '2025-11-19 23:02:19', '2025-11-19 23:02:19'),
(112, 'detecciones/112.jpeg', 'IMG_7716.jpeg', 1.0000, 0.6619, 5, 2, 6, 4, 1091, 1195, NULL, NULL, 'Ubicación no disponible', 9, '181.42.198.160', 'mobile', 'Otro', 'Safari', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-19 23:15:03', '2025-11-19 23:15:03', '2025-11-19 23:15:03'),
(113, 'detecciones/113.jpg', '1000092202.jpg', 1.0000, 0.9940, 5, 9, 4, NULL, 798, 762, -33.594539, -70.379559, 'Los Queltehues, El Peumo Poniente, San José de Maipo, Provincia de Cordillera, Región Metropolitana de Santiago, Chile', 5, '45.239.211.18', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-19 23:39:29', '2025-11-19 23:39:29', '2025-11-19 23:39:29'),
(114, 'detecciones/114.jpg', '1000092202.jpg', 1.0000, 0.9940, 5, 9, 4, 22, 771, 743, -33.594511, -70.379588, 'Los Queltehues, El Peumo Poniente, San José de Maipo, Provincia de Cordillera, Región Metropolitana de Santiago, Chile', 5, '45.239.211.18', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-19 23:40:44', '2025-11-19 23:40:44', '2025-11-19 23:40:45'),
(115, 'detecciones/115.jpg', '1000092202.jpg', 1.0000, 0.9940, 5, 9, 4, 22, 832, 750, -33.594530, -70.379584, 'Los Queltehues, El Peumo Poniente, San José de Maipo, Provincia de Cordillera, Región Metropolitana de Santiago, Chile', 5, '45.239.211.18', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-19 23:41:19', '2025-11-19 23:41:19', '2025-11-19 23:41:19'),
(116, 'detecciones/116.jpg', '1000195760.jpg', 1.0000, 0.6367, 5, 2, 6, 4, 795, 779, -33.504432, -70.651796, '948, Avenida Departamental, Barrio Llico, San Miguel, Provincia de Santiago, Región Metropolitana de Santiago, 8920099, Chile', 10, '191.126.129.131', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-19 23:51:17', '2025-11-19 23:51:17', '2025-11-19 23:51:17'),
(117, 'detecciones/117.jpg', '20251116_140339.jpg', 1.0000, 0.8999, 5, 9, 4, 22, 836, 717, NULL, NULL, 'Ubicación no disponible', 11, '191.118.42.246', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-19 23:59:53', '2025-11-19 23:59:53', '2025-11-19 23:59:54'),
(118, 'detecciones/118.jpg', '1000092240.jpg', 1.0000, 0.9619, 5, 2, 6, 30, 954, 838, NULL, NULL, 'Ubicación no disponible', 5, '186.189.106.179', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-20 07:31:11', '2025-11-20 07:31:11', '2025-11-20 07:31:12'),
(119, 'detecciones/119.jpeg', 'cb3f9681-ca7d-4993-ae2e-acd8a8ee9517.jpeg', 1.0000, 0.9828, 5, 2, 6, 4, 784, 762, -22.321145, -68.888474, 'Calama, Provincia de El Loa, Región de Antofagasta, Chile', 12, '138.84.34.44', 'mobile', 'Otro', 'Safari', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-20 07:44:06', '2025-11-20 07:44:06', '2025-11-20 07:44:07'),
(120, 'detecciones/120.jpeg', 'vale_test.jpeg', 1.0000, 0.9938, 5, 9, 4, 22, 777, 736, -33.469260, -70.585183, '4390, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 2, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', 'like', '2025-12-03 22:37:36', 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-20 09:11:11', '2025-11-20 09:11:11', '2025-12-03 22:37:36'),
(121, 'detecciones/121.jpeg', 'IMG_3581.jpeg', 1.0000, 0.9988, 5, 2, 6, 30, 766, 763, -32.837085, -70.576470, 'Avenida General Erasmo Escala, Montes Andinos, Los Andes, Provincia de Los Andes, Región de Valparaíso, 2100000, Chile', 13, '191.125.147.219', 'mobile', 'Otro', 'Safari', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-20 09:37:21', '2025-11-20 09:37:21', '2025-11-20 09:37:21'),
(122, 'detecciones/122.png', 'IMG_2719.png', 1.0000, 0.6065, 5, 2, 6, 30, 712, 705, -33.469343, -70.584812, '4424, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'mobile', 'Otro', 'Safari', 'web', 'pendiente', 'sin_revisar', 'like', '2025-12-03 18:41:45', 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-20 17:39:52', '2025-11-20 17:39:52', '2025-12-03 18:41:45'),
(123, 'detecciones/123.jpg', '1000092240.jpg', 1.0000, 0.9619, 5, 2, 6, 30, 744, 674, -33.407758, -70.568897, '5920, Avenida Alonso de Córdova, Barrio El Faro, Las Condes, Santiago, Provincia de Santiago, Región Metropolitana de Santiago, 7560942, Chile', 5, '186.189.76.111', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-20 19:13:31', '2025-11-20 19:13:31', '2025-11-20 19:13:32'),
(124, 'detecciones/124.jpg', 'image.jpg', 0.6395, 0.0000, 5, 6, NULL, NULL, 1538, 0, -33.648902, -70.541152, 'Museo Chupacabras de Pirque, Camino La Esperanza, La Esperanza, Pirque, Provincia de Cordillera, Región Metropolitana de Santiago, 8150000, Chile', NULL, '186.10.195.7', 'mobile', 'Otro', 'Safari', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 16:04:25', '2025-11-21 16:04:25', '2025-11-21 16:04:27'),
(125, 'detecciones/125.jpeg', 'IMG_7667.jpeg', 0.9998, 0.3729, 5, 9, 4, 34, 749, 1647, -33.648966, -70.541061, 'Museo Chupacabras de Pirque, Camino La Esperanza, La Esperanza, Pirque, Provincia de Cordillera, Región Metropolitana de Santiago, 8150000, Chile', NULL, '186.10.195.7', 'mobile', 'Otro', 'Safari', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 16:11:59', '2025-11-21 16:11:59', '2025-11-21 16:11:59'),
(126, 'detecciones/126.jpeg', 'IMG_7674.jpeg', 0.6435, 0.0000, 5, 6, NULL, NULL, 766, 0, -33.648697, -70.541252, 'Museo Chupacabras de Pirque, Camino La Esperanza, La Esperanza, Pirque, Provincia de Cordillera, Región Metropolitana de Santiago, 8150000, Chile', 4, '186.10.195.7', 'mobile', 'Otro', 'Safari', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 17:04:17', '2025-11-21 17:04:17', '2025-11-21 17:04:17');
INSERT INTO `detecciones` (`det_id`, `det_imagen`, `det_origen_archivo`, `det_confianza_router`, `det_confianza_experto`, `det_modelo_router_id`, `det_modelo_experto_id`, `det_tax_id`, `det_esp_id`, `det_tiempo_router_ms`, `det_tiempo_experto_ms`, `det_latitud`, `det_longitud`, `det_ubicacion_textual`, `det_obs_id`, `det_ip_cliente`, `det_dispositivo_tipo`, `det_sistema_operativo`, `det_navegador`, `det_fuente`, `det_estado`, `det_revision_estado`, `det_feedback_usuario`, `det_feedback_fecha`, `det_fb_estado`, `det_fb_tax_id`, `det_fb_comentario`, `det_fb_fecha`, `det_observaciones`, `det_validado_por`, `det_validacion_fecha`, `det_fecha`, `created_at`, `updated_at`) VALUES
(127, 'detecciones/127.jpeg', 'IMG_7668.jpeg', 0.9994, 0.5176, 5, 9, 4, 34, 725, 722, -33.648833, -70.541188, 'Museo Chupacabras de Pirque, Camino La Esperanza, La Esperanza, Pirque, Provincia de Cordillera, Región Metropolitana de Santiago, 8150000, Chile', 4, '186.10.195.7', 'mobile', 'Otro', 'Safari', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 17:05:26', '2025-11-21 17:05:26', '2025-11-21 17:05:27'),
(128, 'detecciones/128.jpeg', 'IMG_7657.jpeg', 0.9738, 0.3716, 5, 9, 4, 23, 810, 708, -33.648856, -70.541177, 'Museo Chupacabras de Pirque, Camino La Esperanza, La Esperanza, Pirque, Provincia de Cordillera, Región Metropolitana de Santiago, 8150000, Chile', 4, '186.10.195.7', 'mobile', 'Otro', 'Safari', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 17:06:01', '2025-11-21 17:06:01', '2025-11-21 17:06:02'),
(129, 'detecciones/129.jpeg', 'IMG_7659.jpeg', 0.9918, 0.6243, 5, 9, 4, 23, 858, 688, -33.648886, -70.541161, 'Museo Chupacabras de Pirque, Camino La Esperanza, La Esperanza, Pirque, Provincia de Cordillera, Región Metropolitana de Santiago, 8150000, Chile', 4, '186.10.195.7', 'mobile', 'Otro', 'Safari', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 17:06:25', '2025-11-21 17:06:25', '2025-11-21 17:06:26'),
(130, 'detecciones/130.jpeg', 'IMG_7653.jpeg', 0.9987, 0.8771, 5, 2, 6, 20, 764, 762, -33.648890, -70.541157, 'Museo Chupacabras de Pirque, Camino La Esperanza, La Esperanza, Pirque, Provincia de Cordillera, Región Metropolitana de Santiago, 8150000, Chile', 4, '186.10.195.7', 'mobile', 'Otro', 'Safari', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 17:06:54', '2025-11-21 17:06:54', '2025-11-21 17:06:55'),
(131, 'detecciones/131.jpeg', 'IMG_7651.jpeg', 0.6602, 0.5000, 5, 9, 4, 21, 759, 695, -33.648900, -70.541153, 'Museo Chupacabras de Pirque, Camino La Esperanza, La Esperanza, Pirque, Provincia de Cordillera, Región Metropolitana de Santiago, 8150000, Chile', 4, '186.10.195.7', 'mobile', 'Otro', 'Safari', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 17:07:19', '2025-11-21 17:07:19', '2025-11-21 17:07:19'),
(132, 'detecciones/132.jpg', '20251016_203605.jpg', 1.0000, 0.8219, 5, 2, 6, 20, 875, 688, -33.612369, -70.569401, '549, Elvira Matte, Población Nueva Esperanza, Puente Alto, Provincia de Cordillera, Región Metropolitana de Santiago, 9480000, Chile', 14, '181.43.227.246', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 17:19:46', '2025-11-21 17:19:46', '2025-11-21 17:19:47'),
(133, 'detecciones/133.jpg', '20251016_203605.jpg', 1.0000, 0.8219, 5, 2, 6, 20, 796, 751, -33.612333, -70.570368, 'Aguirre Luco / Eyzaguirre, Avenida Eyzaguirre, Población Nueva Esperanza, Puente Alto, Provincia de Cordillera, Región Metropolitana de Santiago, 8150000, Chile', 14, '181.43.227.246', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 17:20:09', '2025-11-21 17:20:09', '2025-11-21 17:20:10'),
(134, 'detecciones/134.jpg', '1000092240.jpg', 1.0000, 0.9619, 5, 2, 6, 30, 800, 737, -33.420022, -70.612054, 'Autopista Costanera Norte, Pedro de Valdivia Norte, Providencia, Provincia de Santiago, Región Metropolitana de Santiago, 7500000, Chile', 5, '45.232.92.111', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-22 13:59:03', '2025-11-22 13:59:03', '2025-11-22 13:59:03'),
(135, 'detecciones/135.jpg', '1000092202.jpg', 1.0000, 0.9940, 5, 9, 4, 22, 908, 775, -33.420022, -70.612054, 'Autopista Costanera Norte, Pedro de Valdivia Norte, Providencia, Provincia de Santiago, Región Metropolitana de Santiago, 7500000, Chile', 5, '45.232.92.111', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-22 13:59:43', '2025-11-22 13:59:43', '2025-11-22 13:59:43'),
(136, 'detecciones/136.jpg', 'inbound8575389127736747819.jpg', 0.9991, 0.8909, 5, 9, 4, 21, 1361, 1645, -51.741672, -72.457790, 'Ruta Y-330, Glaciar Serrano, Natales, Huertos Endesa, Provincia de Última Esperanza, Región de Magallanes y de la Antártica Chilena, Chile', 8, '191.127.12.16', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-24 13:28:22', '2025-11-24 13:28:22', '2025-11-24 13:28:23'),
(137, 'detecciones/137.jpg', 'inbound3796601318380145600.jpg', 0.9991, 0.8909, 5, 9, 4, 21, 3099, 2158, NULL, NULL, 'Ubicación no disponible', 8, '138.84.33.40', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-02 13:20:32', '2025-12-02 13:20:32', '2025-12-02 13:20:32'),
(138, 'detecciones/138.jpg', 'inbound4142543756195798703.jpg', 0.9991, 0.8909, 5, 9, 4, 21, 885, 793, -50.971622, -72.875539, 'Coiron, Las Torres a Chileno, Albergue Chileno, Torres del Paine, Provincia de Última Esperanza, Región de Magallanes y de la Antártica Chilena, Chile', 8, '138.84.33.40', 'mobile', 'Linux', 'Chrome', 'web', 'pendiente', 'sin_revisar', NULL, NULL, 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-02 13:21:24', '2025-12-02 13:21:24', '2025-12-02 13:21:25'),
(139, 'detecciones/139.jpg', 'Leistes loyca.jpg', 1.0000, 0.9924, 5, 9, 4, 25, 944, 818, -33.469308, -70.584960, '4422, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', 'like', '2025-12-03 18:41:39', 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-02 17:53:31', '2025-12-02 17:53:31', '2025-12-03 18:41:39'),
(140, 'detecciones/140.webp', 'Myocastor coypus.webp', 1.0000, 0.7529, 5, 2, 6, 7, 845, 2296, -33.469566, -70.585203, '4397, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', 'like', '2025-12-03 18:41:41', 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-02 17:59:40', '2025-12-02 17:59:40', '2025-12-03 18:41:41'),
(141, 'detecciones/141.jpeg', 'caballo2.jpeg', 1.0000, 0.9927, 5, 2, 6, 30, 926, 832, -33.469156, -70.584793, '4416, Peatones 15, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', 'like', '2025-12-03 18:41:43', 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-02 21:28:24', '2025-12-02 21:28:24', '2025-12-03 18:41:43'),
(142, 'detecciones/142.jpeg', 'IMG_7217.jpeg', 1.0000, 0.8881, 5, 2, 6, 20, 841, 881, -33.469334, -70.584817, '4424, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 2, '190.114.38.97', 'mobile', 'Otro', 'Safari', 'web', 'validada', 'en_revision', 'dislike', '2025-12-03 22:37:34', 'no_evaluada', NULL, NULL, NULL, '', 1, '2025-12-03 13:13:26', '2025-12-02 21:29:45', '2025-12-02 21:29:46', '2025-12-03 22:37:34'),
(143, 'detecciones/143.jpeg', 'caballo2.jpeg', 1.0000, 0.9927, 5, 2, 6, 30, 792, 725, -33.469023, -70.585107, '4378, Peatones 15, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', 'like', '2025-12-03 15:41:22', 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-03 13:28:52', '2025-12-03 13:28:52', '2025-12-03 15:41:22'),
(144, 'detecciones/144.jpg', 'coyote.jpg', 1.0000, 0.4692, 5, 2, 6, 16, 790, 692, -33.469320, -70.585102, '4408, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'rechazada', 'revisada', 'dislike', '2025-12-03 15:41:35', 'no_evaluada', NULL, NULL, NULL, '<p>efectivamente no coincide.</p>', 1, '2025-12-03 18:45:46', '2025-12-03 13:37:09', '2025-12-03 13:37:09', '2025-12-03 18:45:46'),
(145, 'detecciones/145.webp', 'Equus ferus caballus.webp', 1.0000, 0.9969, 5, 2, 6, 30, 831, 731, -33.469349, -70.585107, '4408, Pasaje 16, Villa Los Jardines, Ñuñoa, Provincia de Santiago, Región Metropolitana de Santiago, 7760247, Chile', 1, '190.114.38.97', 'desktop', 'macOS', 'Chrome', 'web', 'pendiente', 'sin_revisar', 'like', '2025-12-03 18:41:34', 'no_evaluada', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-03 18:39:33', '2025-12-03 18:39:33', '2025-12-03 18:41:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dispositivos`
--

CREATE TABLE `dispositivos` (
  `dis_id` int(10) UNSIGNED NOT NULL COMMENT 'Identificador único del dispositivo registrado',
  `dis_tipo` enum('desktop','mobile','tablet','camera','api') DEFAULT 'desktop' COMMENT 'Tipo de dispositivo utilizado',
  `dis_sistema_operativo` enum('Windows','macOS','Linux','Android','iOS','Otro') DEFAULT 'Otro' COMMENT 'Sistema operativo detectado',
  `dis_navegador` enum('Chrome','Safari','Firefox','Edge','Otro') DEFAULT 'Otro' COMMENT 'Navegador o cliente usado',
  `dis_user_agent` varchar(255) DEFAULT NULL COMMENT 'Cadena completa del user agent',
  `dis_ip_origen` varchar(45) DEFAULT NULL COMMENT 'Dirección IP pública del dispositivo',
  `dis_usuario_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'Identificador lógico del usuario asociado',
  `created_at` datetime DEFAULT current_timestamp() COMMENT 'Fecha de registro del dispositivo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Registro de dispositivos utilizados en EcoLens.';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `emails`
--

CREATE TABLE `emails` (
  `ema_id` int(10) UNSIGNED NOT NULL COMMENT 'Identificador único de Emails',
  `ema_asunto` varchar(100) DEFAULT NULL COMMENT 'Asunto del Email',
  `ema_cuerpo` mediumtext DEFAULT NULL COMMENT 'Cuerpo del Email',
  `ema_hora` datetime NOT NULL COMMENT 'Hora de envío de email',
  `ema_para` varchar(200) NOT NULL COMMENT 'Destinatarios del Email',
  `ema_concopia` varchar(45) DEFAULT NULL COMMENT 'Destinatarios con copia del Email',
  `ema_concopiaoculta` varchar(45) DEFAULT NULL COMMENT 'Destinatarios con copia oculta del Email',
  `ema_ip` varchar(20) NOT NULL COMMENT 'IP de dónde se origina el email',
  `ema_estado` enum('BORRADOR','ENVIADO') NOT NULL COMMENT 'Estado de envío del email',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especies`
--

CREATE TABLE `especies` (
  `esp_id` int(10) UNSIGNED NOT NULL COMMENT 'Identificador único de la especie',
  `esp_nombre_cientifico` varchar(255) NOT NULL COMMENT 'Nombre científico de la especie',
  `esp_slug` varchar(255) DEFAULT NULL COMMENT 'Slug de la especie',
  `esp_nombre_comun` varchar(255) DEFAULT NULL COMMENT 'Nombre común de la especie (ej. Puma, Pez espada)',
  `esp_tax_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'Identificador lógico de la clase taxonómica (taxonomias.tax_id)',
  `esp_descripcion` text DEFAULT NULL COMMENT 'Descripción general de la especie',
  `esp_imagen` varchar(255) DEFAULT NULL COMMENT 'Imagen representativa de la especie',
  `esp_estado` enum('activo','inactivo') DEFAULT 'activo' COMMENT 'Indica si la especie está disponible para detección',
  `created_at` datetime DEFAULT current_timestamp() COMMENT 'Fecha de creación del registro',
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Fecha de última modificación',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'Usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'Usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Listado de especies registradas en EcoLens.';

--
-- Volcado de datos para la tabla `especies`
--

INSERT INTO `especies` (`esp_id`, `esp_nombre_cientifico`, `esp_slug`, `esp_nombre_comun`, `esp_tax_id`, `esp_descripcion`, `esp_imagen`, `esp_estado`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'Canis Latrans', 'canis-latrans', 'Coyote', 6, '<p><span dir=\"auto\">Canis latrans</span><span dir=\"auto\"> , com&uacute;nmente conocida como coyote, es un c&aacute;nido silvestre originario de Norte y Centroam&eacute;rica. Aunque no es nativa de Chile, es una especie de gran inter&eacute;s por su notable capacidad de adaptaci&oacute;n y su rol ecol&oacute;gico en diversos ecosistemas, expandiendo su rango a trav&eacute;s del continente americano.</span></p>\r\n<p><strong><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul>\r\n<li>\r\n<p><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es un c&aacute;nido de tama&ntilde;o mediano (longitud cabeza-cuerpo de 75-100 cm; peso de 9-23 kg), con una apariencia esbelta y atl&eacute;tica. Su pelaje es generalmente gris&aacute;ceo a marr&oacute;n rojizo en la espalda, con un vientre m&aacute;s claro y manchas oscuras en los hombros. Posee orejas grandes y erguidas, un hocico puntiagudo y una cola tupida que suele llevar baja</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">H&aacute;bitat y distribuci&oacute;n:</span></strong><span dir=\"auto\"> El coyote es nativo de Norteam&eacute;rica, desde Canad&aacute; hasta Centroam&eacute;rica. Ha demostrado una asombrosa capacidad de adaptaci&oacute;n, prosperando en una amplia gama de h&aacute;bitats que incluyen praderas, bosques, desiertos, monta&ntilde;as e incluso &aacute;reas suburbanas y urbanas. Su &eacute;xito se debe a su flexibilidad diet&eacute;tica y conductual.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Ecolog&iacute;a y comportamiento:</span></strong><span dir=\"auto\"> Es un depredador generalista y oportunista, y su dieta es extremadamente variada. Consume peque&ntilde;os mam&iacute;feros (roedores, conejos), aves, insectos, reptiles, carro&ntilde;a y una cantidad significativa de materia vegetal (frutos, bayas). Cazan solos, en parejas o en peque&ntilde;os grupos, dependiendo del tama&ntilde;o de la presa y la disponibilidad de recursos. Son conocidos por sus aullidos distintivos, que utilizan para la comunicaci&oacute;n territorial y social</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Estado de conservaci&oacute;n y relevancia:</span></strong><span dir=\"auto\"> El coyote es una de las pocas especies de grandes depredadores cuya poblaci&oacute;n y rango se han expandido a pesar de la presi&oacute;n humana. Se considera de \"preocupaci&oacute;n menor\" a nivel global. Juega un papel ecol&oacute;gico importante como regulador de poblaciones de mesomam&iacute;feros y como dispersor de semillas, impactando significativamente la estructura y funci&oacute;n de los ecosistemas en los que habita.</span></p>\r\n</li>\r\n</ul>', 'especies/1.jpg', 'inactivo', '2025-10-16 20:22:08', '2025-12-03 22:38:58', 1, 1),
(2, 'Lynx rufus', 'lynx-rufus', 'Lince rojo', 6, '<p><span dir=\"auto\">Lynx rufus</span><span dir=\"auto\"> , com&uacute;nmente conocida como lince rojo o bobcat, es un felino salvaje de tama&ntilde;o mediano nativo de Norteam&eacute;rica. Aunque no se encuentra en Chile, es una especie de gran inter&eacute;s por su notable adaptabilidad y su papel como depredador clave en una amplia gama de ecosistemas en su continente de origen</span></p>\r\n<p><strong><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul>\r\n<li>\r\n<p><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es un felino robusto de patas relativamente largas y cola corta y truncada (\"bobbed\" en ingl&eacute;s, de ah&iacute; su nombre com&uacute;n). Su longitud cabeza-cuerpo var&iacute;a entre 65 y 105 cm, con un peso de 6 a 14 kg, siendo los machos ligeramente m&aacute;s grandes. Su pelaje es denso y suave, de color variable (gris a marr&oacute;n rojizo) con manchas oscuras o rayas, que le proporcionan un excelente camuflaje. Presenta mechones de pelo en las orejas, caracter&iacute;sticos de los linces, ya a menudo un \"collar\" de pelaje en las mejillas.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n:</span></strong><span dir=\"auto\"> El lince rojo tiene una de las distribuciones m&aacute;s amplias de cualquier felino en Norteam&eacute;rica, encontr&aacute;ndose desde el sur de Canad&aacute; hasta el sur de M&eacute;xico. Habita una gran diversidad de ecosistemas, incluyendo bosques, desiertos, pantanos, monta&ntilde;as y &aacute;reas agr&iacute;colas, e incluso se adapta a los suburbios, siempre que disponga de cobertura y presas</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Es un depredador carn&iacute;voro y oportunista, cuya dieta se compone principalmente de mam&iacute;feros peque&ntilde;os y medianos, como conejos, liebres, roedores y, en menor medida, aves, reptiles y ciervos j&oacute;venes. Es un cazador sigiloso, principalmente crepuscular y nocturno. Son animales solitarios y territoriales, marcando su presencia con orina, heces y ara&ntilde;azos.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\"> A pesar de haber sido hist&oacute;ricamente cazado por su piel y por conflictos con el ganado, el lince rojo mantiene poblaciones estables en gran parte de su rango y est&aacute; clasificado como de \"Preocupaci&oacute;n Menor\" a nivel global. Su capacidad para prosperar en ambientes alterados y su rol como controlador de poblaciones de herb&iacute;voros lo convierten en un componente ecol&oacute;gico importante en los ecosistemas de Norteam&eacute;rica</span></p>\r\n</li>\r\n</ul>', 'especies/2.jpg', 'activo', '2025-10-17 10:14:43', '2025-10-29 19:39:37', 1, 5),
(3, 'Otaria flavescens', 'otaria-flavescens', 'Lobo Marino de un pelo ', 6, '<p><span dir=\"auto\">La </span><strong><span dir=\"auto\">Otaria flavescens</span></strong><span dir=\"auto\"> , conocida como lobo marino com&uacute;n o le&oacute;n marino sudamericano, es una especie de ot&aacute;rido emblem&aacute;tica y ampliamente distribuida a lo largo de las costas de Chile, desde la Regi&oacute;n de Arica y Parinacota hasta las islas subant&aacute;rticas.</span></p>\r\n<p><strong><span dir=\"auto\">Caracter&iacute;sticas Clave:</span></strong></p>\r\n<ul>\r\n<li>\r\n<p><strong><span dir=\"auto\">Dimorfismo Sexual:</span></strong><span dir=\"auto\"> Presenta un marcado dimorfismo sexual; los machos son de mayor tama&ntilde;o (hasta 2.5 my 300+ kg), con una prominente melena y cuello robusto, mientras que las hembras son m&aacute;s peque&ntilde;as (hasta 2 my 150 kg). Su pelaje var&iacute;a del caf&eacute; claro a oscuro.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n:</span></strong><span dir=\"auto\"> Ocupa una diversidad de h&aacute;bitats costeros, incluyendo playas arenosas, costas rocosas e islotes. Forma grandes colonias reproductivas y de descanso, siendo un componente visual y ac&uacute;stico distintivo del paisaje litoral chileno.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Es un depredador tope oportunista cuya dieta consiste principalmente en peces, cefal&oacute;podos y crust&aacute;ceos. Son animales sociales, organizados en harenes durante la &eacute;poca reproductiva, con complejas vocalizaciones para la comunicaci&oacute;n.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia en Chile:</span></strong><span dir=\"auto\"> Aunque su poblaci&oacute;n es estable y est&aacute; legalmente protegida en Chile, enfrenta desaf&iacute;os derivados de la interacci&oacute;n con pesquer&iacute;as y la contaminaci&oacute;n. Su presencia es un indicador clave de la salud ecosist&eacute;mica marina y un recurso importante para el ecoturismo y la investigaci&oacute;n cient&iacute;fica en el pa&iacute;s.</span></p>\r\n</li>\r\n</ul>', 'especies/3.jpg', 'inactivo', '2025-10-28 23:30:49', '2025-11-19 09:35:20', 5, 5),
(4, 'Lama guanicoe', 'lama-guanicoe', 'Guanaco', 6, '<p><strong><span dir=\"auto\">Lama guanicoe: El Guanaco, Rumiante Emblem&aacute;tico de los Andes y la Patagonia Chilena</span></strong></p>\r\n<p><span dir=\"auto\">La </span><span dir=\"auto\">Lama guanicoe</span><span dir=\"auto\"> , conocida como guanaco, es un cam&eacute;lido sudamericano silvestre, considerado el ancestro de la llama dom&eacute;stica. Es una especie ic&oacute;nica de los paisajes altoandinos y patag&oacute;nicos de Chile, adaptada a condiciones extremas de altitud y clima.</span></p>\r\n<p><strong><span dir=\"auto\">Caracter&iacute;sticas Clave:</span></strong></p>\r\n<ul>\r\n<li>\r\n<p><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Posee un cuerpo esbelto y patas largas, cubierto por un pelaje denso y lanoso de color caf&eacute; claro a rojizo en el dorso, que se aclara a blanquecino en el vientre y las patas internas. Su cabeza es de color gris&aacute;ceo con orejas largas y puntiagudas. Los adultos miden entre 1,5 y 1,9 metros de altura a la cruz y pesan entre 90 y 140 kg.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n en Chile:</span></strong><span dir=\"auto\"> En Chile, el guanaco se distribuye desde la Regi&oacute;n de Arica y Parinacota hasta Tierra del Fuego. Habita una amplia variedad de ecosistemas, desde estepas altoandinas y desiertos fr&iacute;os hasta pastizales patag&oacute;nicos, prefiriendo zonas con vegetaci&oacute;n abierta que le permiten detectar depredadores.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Es un herb&iacute;voro que se alimenta de pastos, arbustos y l&iacute;quenes, adaptando su dieta seg&uacute;n la disponibilidad estacional. Vive en grupos sociales que var&iacute;an desde harenes (un macho, varias hembras y sus cr&iacute;as) hasta grupos de machos solteros. Son animales altamente adaptados a la escasez de agua, obteniendo gran parte de ella de la vegetaci&oacute;n que consumen. Son r&aacute;pidos y &aacute;giles, claves habilidades para evadir a depredadores como el puma.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\"> Aunque su poblaci&oacute;n ha disminuido hist&oacute;ricamente debido a la caza y la competencia con el ganado, actualmente goza de protecci&oacute;n y sus poblaciones se han recuperado en algunas &aacute;reas. Es una especie clave en el mantenimiento de la biodiversidad de los ecosistemas donde habita, actuando como dispersor de semillas y contribuyendo a la salud de los pastizales. Su conservaci&oacute;n es fundamental para la integridad ecol&oacute;gica de los Andes y la Patagonia.</span></p>\r\n</li>\r\n</ul>', 'especies/4.jpg', 'activo', '2025-10-28 23:35:48', '2025-11-19 23:01:18', 5, 5),
(5, 'Lycalopex grisea', 'lycalopex-grisea', 'Zorro Chilla', 6, '<p><strong><span dir=\"auto\">Lycalopex grisea: El Zorro Chilla, Carn&iacute;voro Resiliente de los Ecosistemas Chilenos</span></strong></p>\r\n<p><span dir=\"auto\">La </span><span dir=\"auto\">Lycalopex grisea</span><span dir=\"auto\"> , conocida como zorro chilla o zorro gris, es un c&aacute;nido silvestre end&eacute;mico de Sudam&eacute;rica, ampliamente distribuido en Chile. Es una especie clave en la cadena tr&oacute;fica de diversos ecosistemas chilenos, desde el desierto costero hasta la Patagonia.</span></p>\r\n<p><strong><span dir=\"auto\">Caracter&iacute;sticas Clave:</span></strong></p>\r\n<ul>\r\n<li>\r\n<p><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es un zorro de tama&ntilde;o mediano (longitud cabeza-cuerpo de 43-70 cm; peso de 2.5-5.4 kg), m&aacute;s peque&ntilde;o que el zorro culpeo. Su pelaje es gris&aacute;ceo en el dorso con tonos rojizos o leonados en los flancos y patas. Presenta una mancha oscura en el lomo que se extiende hasta la cola, la cual es relativamente corta y de punta negra.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n en Chile:</span></strong><span dir=\"auto\"> En Chile, el zorro chilla se encuentra en una vasta extensi&oacute;n geogr&aacute;fica, desde la Regi&oacute;n de Atacama hasta la Regi&oacute;n de Magallanes. Es altamente adaptable y ocupa una diversidad de h&aacute;bitats, incluyendo matorrales, estepas, bosques degradados, dunas costeras y ambientes agr&iacute;colas, evitando generalmente las zonas de alta monta&ntilde;a y los bosques densos.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Es un animal principalmente nocturno o crepuscular, aunque puede estar activo durante el d&iacute;a, especialmente en invierno o en zonas con baja presi&oacute;n antr&oacute;pica. Su dieta es omn&iacute;vora y muy oportunista, a incluir peque&ntilde;os mam&iacute;feros (roedores, conejos), aves, huevos, reptiles, insectos y una importante proporci&oacute;n de frutos y carro&ntilde;a. Vive solitario o en parejas durante la &eacute;poca reproductiva.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\"> A pesar de haber sido hist&oacute;ricamente perseguido por considerar un depredador de ganado menor, su poblaci&oacute;n se considera estable y no est&aacute; categorizada como amenazada a nivel nacional en Chile, aunque su estado puede variar regionalmente. Cumple un rol ecol&oacute;gico vital como controlador de poblaciones de roedores y dispersor de semillas, contribuyendo a la salud y equilibrio de los ecosistemas donde habita.</span></p>\r\n</li>\r\n</ul>', 'especies/5.jpg', 'inactivo', '2025-10-28 23:38:16', '2025-11-19 09:14:19', 5, 5),
(6, 'Lycalopex culpaeus', 'lycalopex-culpaeus', 'Zorro Culpeo', 6, '<p><strong><span dir=\"auto\">Lycalopex culpaeus: El Zorro Culpeo, El Mayor C&aacute;nido Silvestre de Chile</span></strong></p>\r\n<p><span dir=\"auto\">La </span><span dir=\"auto\">Lycalopex culpaeus</span><span dir=\"auto\"> , com&uacute;nmente conocido como zorro culpeo, es el c&aacute;nido silvestre m&aacute;s grande de Chile y el segundo c&aacute;nido m&aacute;s grande de Sudam&eacute;rica. Es una especie adaptada a una amplia gama de ambientes, desde los desiertos del norte hasta los bosques patag&oacute;nicos, desempe&ntilde;ando un papel crucial como tope depredador en sus ecosistemas.</span></p>\r\n<p><strong><span dir=\"auto\">Caracter&iacute;sticas Clave:</span></strong></p>\r\n<ul>\r\n<li>\r\n<p><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es un zorro de tama&ntilde;o considerable (longitud cabeza-cuerpo de 60-115 cm; peso de 5-13.5 kg), con un aspecto robusto similar al de un peque&ntilde;o coyote. Su pelaje es denso, de coloraci&oacute;n variable seg&uacute;n la regi&oacute;n, generalmente gris-rojizo en la espalda, con un vientre m&aacute;s claro y las patas rojizas. La cola es larga y tupida, con la punta negra, y la parte posterior de las orejas es rojiza.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n en Chile:</span></strong><span dir=\"auto\"> El zorro culpeo tiene una amplia distribuci&oacute;n en Chile, encontr&aacute;ndose desde el altiplano de Arica y Parinacota hasta la Regi&oacute;n de Magallanes. Habita una gran diversidad de ecosistemas, incluyendo desiertos &aacute;ridos, estepas, matorrales, bosques templados, ambientes andinos y patag&oacute;nicos, demostrando una gran capacidad de adaptaci&oacute;n.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Es un depredador generalista y oportunista. Su dieta es muy variada e incluye mam&iacute;feros peque&ntilde;os y medianos (como roedores, conejos, liebres, e incluso cr&iacute;as de cam&eacute;lidos), aves, reptiles, insectos y, en menor medida, frutos. Puede ser activo tanto de d&iacute;a como de noche, dependiendo de la presi&oacute;n humana y la disponibilidad de presas. Vive solitario o en parejas, especialmente durante la &eacute;poca reproductiva.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\"> Aunque ha sido objeto de persecuci&oacute;n debido a su depredaci&oacute;n sobre el ganado, el zorro culpeo mantiene poblaciones estables en gran parte de su rango en Chile. Est&aacute; clasificado como \"Preocupaci&oacute;n Menor\" a nivel nacional. Su rol como regulador de poblaciones de herb&iacute;voros y roedores es fundamental para el equilibrio ecol&oacute;gico de los diversos paisajes chilenos.</span></p>\r\n</li>\r\n</ul>', 'especies/6.jpg', 'activo', '2025-10-28 23:40:18', '2025-10-28 23:40:39', 5, 5),
(7, 'Myocastor Coypus', 'myocastor-coypus', 'Coipo', 6, '<p><em><span dir=\"auto\">Myocastor coypus</span></em><span dir=\"auto\"> (coipo) y una imagen realista, adecuada para una tesis.</span></p>\r\n<hr>\r\n<p><strong><span dir=\"auto\">Myocastor coypus: El Coipo, Roedor Semiacu&aacute;tico de los Humedales Chilenos</span></strong></p>\r\n<p><span dir=\"auto\">La </span><span dir=\"auto\">Myocastor coypus</span><span dir=\"auto\"> , conocida como coipo o nutria roedora, es un roedor semiacu&aacute;tico de gran tama&ntilde;o, nativo de Sudam&eacute;rica, con una presencia importante en los humedales y cuerpos de agua dulce de Chile. Es un animal herb&iacute;voro bien adaptado a la vida en el agua y un componente distintivo de la fauna de las cuencas hidrogr&aacute;ficas.</span></p>\r\n<p><strong><span dir=\"auto\">Caracter&iacute;sticas Clave:</span></strong></p>\r\n<ul>\r\n<li>\r\n<p><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es el roedor m&aacute;s grande de Chile, con una longitud cabeza-cuerpo que puede alcanzar los 60 cm y un peso de 5 a 9 kg. Su cuerpo es robusto, cubierto por un pelaje denso y pardo, con una capa interna de subpelo gris oscuro muy fino y valorado hist&oacute;ricamente por su piel. Posee una cola larga, cil&iacute;ndrica y escamosa, y patas traseras palmeadas, adaptaciones perfectas para la nataci&oacute;n. Sus incisivos son grandes y de un caracter&iacute;stico color anaranjado brillante.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n en Chile:</span></strong><span dir=\"auto\"> En Chile, el coipo se distribuye principalmente desde la Regi&oacute;n de Coquimbo hasta la Regi&oacute;n de Los Lagos, aunque su distribuci&oacute;n puede ser discontinua y ha habido avistamientos m&aacute;s al sur. Habita en una variedad de ambientes acu&aacute;ticos de agua dulce o salobre, incluyendo r&iacute;os, lagos, lagunas, pantanos, esteros y zonas de marisma, donde la vegetaci&oacute;n ribere&ntilde;a es abundante.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Es un animal principalmente herb&iacute;voro, aliment&aacute;ndose de tallos, hojas, ra&iacute;ces y rizomas de plantas acu&aacute;ticas y terrestres. Construye madrigueras complejas en las riberas o utiliza plataformas de vegetaci&oacute;n flotante. Es predominantemente crepuscular y nocturno, aunque puede observarse durante el d&iacute;a. Son excelentes nadadores y buceadores, capaces de permanecer sumergidos por varios minutos.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\"> A nivel global, el coipo es considerado una especie invasora en varias partes del mundo debido a fugas de granjas peleteras, causando impactos ecol&oacute;gicos y econ&oacute;micos. Sin embargo, en su rango nativo en Chile, juega un papel ecol&oacute;gico en la estructuraci&oacute;n de la vegetaci&oacute;n de humedales. Su estado de conservaci&oacute;n en Chile no es cr&iacute;tico, pero su presencia es un buen indicador de la salud de los humedales.</span></p>\r\n</li>\r\n</ul>', 'especies/7.jpg', 'inactivo', '2025-10-28 23:42:29', '2025-11-19 09:35:53', 5, 5),
(8, 'Odocoileus hemionus', 'odocoileus-hemionus', 'Venado Bura', 6, '<p><span dir=\"auto\">La </span><span dir=\"auto\">Odocoileus hemionus</span><span dir=\"auto\"> , com&uacute;nmente conocida como venado bura o ciervo mulo, es una especie de c&eacute;rvido nativa de Norteam&eacute;rica. Aunque no se encuentra en Chile, es un herb&iacute;voro de gran importancia ecol&oacute;gica en sus ecosistemas nativos, destac&aacute;ndose por sus grandes orejas que recuerdan a las de una mula, de ah&iacute; su nombre com&uacute;n.</span></p>\r\n<p><strong><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul>\r\n<li>\r\n<p><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es un c&eacute;rvido de tama&ntilde;o mediano a grande, con una altura a la cruz de aproximadamente 80 a 105 cm y un peso que var&iacute;a entre 50 y 150 kg, siendo los machos significativamente m&aacute;s grandes que las hembras. Su pelaje es generalmente gris-marr&oacute;n en invierno y m&aacute;s rojizo en verano, con la parte inferior del cuerpo m&aacute;s clara. La caracter&iacute;stica m&aacute;s distintiva son sus orejas grandes y conspicuas. Los machos poseen astas ramificadas que se renuevan anualmente</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">H&aacute;bitat y distribuci&oacute;n:</span></strong><span dir=\"auto\"> El venado bura se distribuye ampliamente en el oeste de Norteam&eacute;rica, desde el suroeste de Canad&aacute; hasta el centro de M&eacute;xico. Habita una gran variedad de ecosistemas, incluyendo bosques monta&ntilde;osos, chaparrales, desiertos, matorrales y praderas, mostrando una notable adaptabilidad a diferentes condiciones ambientales.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Ecolog&iacute;a y comportamiento:</span></strong><span dir=\"auto\"> Son animales herb&iacute;voros, cuya dieta var&iacute;a estacionalmente e incluye arbustos, hierbas, brotes, frutos y bellotas. Son principalmente crepusculares (activos al amanecer y al atardecer) y nocturnos. Los venados bura son generalmente gregarios, viviendo en peque&ntilde;os grupos, aunque los machos adultos pueden ser m&aacute;s solitarios fuera de la &eacute;poca de apareamiento. Migran estacionalmente entre rangos de verano e invierno en algunas regiones para acceder a alimento y evitar condiciones clim&aacute;ticas extremas</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\"> A pesar de haber enfrentado presi&oacute;n por la caza y la p&eacute;rdida de h&aacute;bitat, las poblaciones de venado bura son generalmente estables en gran parte de su rango, y se consideran de \"Preocupaci&oacute;n Menor\" a nivel global. Juegan un rol ecol&oacute;gico crucial como herb&iacute;voros, afectando la estructura de la vegetaci&oacute;n y sirviendo como presa para grandes carn&iacute;voros como pumas, lobos y osos, contribuyendo al equilibrio de los ecosistemas donde habitan</span></p>\r\n</li>\r\n</ul>', 'especies/8.jpg', 'inactivo', '2025-10-29 19:41:37', '2025-11-19 09:36:03', 5, 5),
(9, 'Otospermophilus beecheyi', 'otospermophilus-beecheyi', 'Ardilla Terrestre de California', 6, '<p><span dir=\"auto\">La </span><span dir=\"auto\">Otospermophilus beecheyi</span><span dir=\"auto\"> , com&uacute;nmente conocida como ardilla terrestre de California, es un roedor diurno nativo de las regiones occidentales de Estados Unidos y M&eacute;xico. Aunque no se encuentra en Chile, es una especie de gran inter&eacute;s ecol&oacute;gico por su papel como excavadora y su impacto en la estructura del suelo y la vegetaci&oacute;n de sus h&aacute;bitats.</span></p>\r\n<p><strong><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul>\r\n<li>\r\n<p><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es una ardilla terrestre de tama&ntilde;o relativamente grande, con una longitud corporal de aproximadamente 23-30 cm y una cola m&aacute;s corta y tupida (13-20 cm). Su pelaje es moteado o gris-marr&oacute;n, con tonos m&aacute;s claros en el vientre ya menudo una \"capucha\" o \"collar\" de pelaje gris&aacute;ceo alrededor del cuello y los hombros. Sus orejas son peque&ntilde;as y tiene ojos grandes y oscuros</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">H&aacute;bitat y distribuci&oacute;n:</span></strong><span dir=\"auto\"> Se distribuye principalmente a lo largo de California, Oreg&oacute;n, Washington (EE. UU.) y la pen&iacute;nsula de Baja California (M&eacute;xico). Habita en una amplia gama de ecosistemas de baja y media elevaci&oacute;n, incluyendo pastizales, chaparrales, matorrales, bosques abiertos, &aacute;reas agr&iacute;colas, zonas suburbanas y parques. Requiere suelos adecuados para excavar sus extensos sistemas de madrigueras</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Es un roedor principalmente herb&iacute;voro, aliment&aacute;ndose de semillas (especialmente de gram&iacute;neas y robles), nueces, frutos, bulbos y brotes verdes. Tambi&eacute;n puede consumir insectos y carro&ntilde;a. Son animales altamente sociales, que viven en colonias y utilizan complejos sistemas de madrigueras subterr&aacute;neas. Son conocidos por su comportamiento de \"centinela\" y sus vocalizaciones de alarma para advertir sobre depredadores como serpientes de cascabel, aves rapaces y coyotes. Pueden entrar en un estado de letargo (hibernaci&oacute;n o estivaci&oacute;n) durante per&iacute;odos de escasez de alimentos o condiciones clim&aacute;ticas adversas.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\"> Las poblaciones de ardilla terrestre de California son abundantes y se consideran de \"Preocupaci&oacute;n Menor\". Juegan un rol ecol&oacute;gico significativo como ingenieros de ecosistemas, ya que sus madrigueras airean el suelo, redistribuyen nutrientes y proporcionan refugio a otras especies. Adem&aacute;s, son una fuente de alimento importante para una variedad de depredadores, contribuyendo al flujo de energ&iacute;a en sus ecosistemas</span></p>\r\n</li>\r\n</ul>', 'especies/9.jpg', 'inactivo', '2025-10-29 19:43:39', '2025-11-19 09:36:30', 5, 5),
(10, 'Phoca vitulina', 'phoca-vitulina', 'Foca', 6, '<p><span dir=\"auto\">La </span><span dir=\"auto\">Phoca vitulina</span><span dir=\"auto\"> , conocida com&uacute;nmente como foca com&uacute;n o foca de puerto, es el pinn&iacute;pedo m&aacute;s extendido del hemisferio norte. Aunque no es nativa de Chile (donde habitan otras especies de focas y lobos marinos), es una especie clave en los ecosistemas costeros de muchas regiones templadas y fr&iacute;as, destacada por su adaptabilidad a una variedad de h&aacute;bitats marinos y estuarinos</span></p>\r\n<p><strong><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul>\r\n<li>\r\n<p><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es una foca de tama&ntilde;o mediano (longitud de 1.2 a 1.9 metros; peso de 55 a 170 kg), con un cuerpo robusto y fusiforme. Su pelaje es corto y denso, con una coloraci&oacute;n muy variable que va del gris plateado al marr&oacute;n oscuro, a menudo con manchas oscuras o anillos irregulares, lo que le permite un excelente camuflaje en el agua y en la costa. Posee una cabeza relativamente peque&ntilde;a y redonda, con un hocico corto y bigotes prominentes (vibrisas). Carecen de orejas externas visibles.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">H&aacute;bitat y distribuci&oacute;n:</span></strong><span dir=\"auto\"> La foca com&uacute;n tiene una distribuci&oacute;n circumpolar en el hemisferio norte, habitando las aguas costeras y estuarinas del Atl&aacute;ntico y Pac&iacute;fico. Se encuentra en latitudes templadas y fr&iacute;as, prefiriendo bah&iacute;as protegidas, estuarios, playas de arena, rocas y plataformas de hielo donde pueden descansar y dar a luz. No migran grandes distancias</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Son depredadores oportunistas, con una dieta diversa que incluye peces (arenque, bacalao, salm&oacute;n, lenguado), cefal&oacute;podos (pulpos, calamares) y crust&aacute;ceos. Son excelentes nadadores y buceadores, capaces de sumergirse a profundidades considerables y durante varios minutos para buscar alimento. Aunque se agrupan en colonias en tierra para descansar (conocido como \"haul out\"), generalmente son solitarios cuando cazan. Las hembras dan a luz a una sola cr&iacute;a bien desarrollada en tierra o hielo, que es capaz de nadar poco despu&eacute;s del nacimiento.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\"> A nivel global, la foca com&uacute;n est&aacute; clasificada como de \"Preocupaci&oacute;n Menor\" y sus poblaciones son generalmente estables o se est&aacute;n recuperando en muchas &aacute;reas tras haber sido cazadas intensamente en el pasado. Juegan un papel importante como depredadores tope, ayudando a mantener el equilibrio en los ecosistemas marinos costeros. Su presencia es un indicador de la salud de estos ambientes</span></p>\r\n</li>\r\n</ul>', 'especies/10.jpg', 'inactivo', '2025-10-29 19:45:31', '2025-11-19 09:37:27', 5, 5),
(11, 'Procyon lotor', 'procyon-lotor', 'Mapache', 6, '<p><span dir=\"auto\">La </span><span dir=\"auto\">Procyon lotor</span><span dir=\"auto\"> , com&uacute;nmente conocida como mapache, es un mam&iacute;fero omn&iacute;voro nativo de Norteam&eacute;rica. Reconocido por su distintiva \"m&aacute;scara\" facial y su cola anillada, el mapache es una de las especies de mesodepredadores m&aacute;s exitosas y adaptables, habiendo expandido su rango a muchas partes del mundo, aunque no es nativa de Chile.</span></p>\r\n<p><strong><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul>\r\n<li>\r\n<p><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es un mam&iacute;fero de tama&ntilde;o mediano (longitud cabeza-cuerpo de 40-70 cm; peso de 3-9 kg), con un cuerpo robusto y patas delanteras prensiles. Su pelaje es denso y gris&aacute;ceo, con variaciones de tonos negros y marrones. Las caracter&iacute;sticas m&aacute;s distintivas son su \"m&aacute;scara\" negra alrededor de los ojos, que contrasta con el pelaje blanco alrededor, y su cola larga y tupida con anillos negros. Sus patas delanteras son muy diestras, lo que les permite manipular objetos con gran habilidad</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">H&aacute;bitat y distribuci&oacute;n:</span></strong><span dir=\"auto\"> Originalmente nativo de Norteam&eacute;rica, el mapache se distribuye desde el sur de Canad&aacute; hasta el norte de Sudam&eacute;rica. Ha sido introducido en varias partes de Europa y Asia, donde a menudo se considera una especie invasora. Es extremadamente adaptable, habitando una amplia gama de ecosistemas que incluyen bosques, pantanos, praderas, &aacute;reas costeras y, con notable &eacute;xito, entornos urbanos y suburbanos.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Ecolog&iacute;a y comportamiento:</span></strong><span dir=\"auto\"> Es un animal omn&iacute;voro y altamente oportunista, lo que contribuye a su &eacute;xito en diversos ambientes. Su dieta es muy variada e incluye invertebrados (insectos, cangrejos, almejas), peque&ntilde;os vertebrados (roedores, aves, huevos, ranas), frutos, nueces, granos y, en &aacute;reas urbanas, basura y alimento para mascotas. Son principalmente nocturnos y solitarios, aunque pueden agruparse en refugios comunes. Son conocidos por su comportamiento de \"lavar\" alimentos, aunque no es una verdadera limpieza, sino m&aacute;s bien un proceso de manipulaci&oacute;n t&aacute;ctil</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\"> Las poblaciones de mapaches son abundantes y estables en la mayor&iacute;a de su rango nativo, y est&aacute;n clasificadas como de \"Preocupaci&oacute;n Menor\". En las regiones donde ha sido introducido, puede causar impactos negativos como la depredaci&oacute;n de especies nativas y la transmisi&oacute;n de enfermedades. Sin embargo, en sus h&aacute;bitats nativos, juega un rol ecol&oacute;gico como controlador de plagas y dispersor de semillas, y su adaptabilidad lo convierte en un sujeto de estudio interesante para la ecolog&iacute;a urbana y la biolog&iacute;a de la invasi&oacute;n</span></p>\r\n</li>\r\n</ul>', 'especies/11.jpg', 'inactivo', '2025-10-29 19:48:37', '2025-11-19 09:14:48', 5, 5),
(12, 'Lama Vicugna', 'lama-vicugna', 'Vicuña', 6, '<p><span dir=\"auto\">Lama vicugna: La Vicu&ntilde;a, Joya de los Andes y Productora de la Fibra M&aacute;s Fina</span></p>\r\n<p><span dir=\"auto\">La </span><span dir=\"auto\">Lama vicugna</span><span dir=\"auto\"> , conocida como vicu&ntilde;a, es un cam&eacute;lido sudamericano silvestre, el m&aacute;s peque&ntilde;o y esbelto de su familia. Es una especie emblem&aacute;tica de los ecosistemas de alta monta&ntilde;a andina, valorada por su excepcional fibra, una de las m&aacute;s finas del mundo, y por su adaptaci&oacute;n a las condiciones extremas de altitud y aridez.</span></p>\r\n<p><strong><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul>\r\n<li>\r\n<p><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es el cam&eacute;lido m&aacute;s peque&ntilde;o, con una altura a la cruz de aproximadamente 75-90 cm y un peso de 35-65 kg. Su pelaje es notable fino, denso y de color canela o marr&oacute;n claro en el dorso, con el vientre y la parte interna de las patas de color blanco. Presenta un mech&oacute;n de pelo largo y blanco en el pecho, distintivo de la especie. Su cabeza es peque&ntilde;a y sus orejas son largas y puntiagudas.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n en Chile:</span></strong><span dir=\"auto\"> En Chile, la vicu&ntilde;a se encuentra en el altiplano andino de las regiones del norte, principalmente desde Arica y Parinacota hasta Atacama, en altitudes que superan los 3.500 metros sobre el nivel del mar. Habita en estepas altoandinas y punas, caracterizadas por pastizales y bofedales (humedales de altura), vitales para su alimentaci&oacute;n y supervivencia.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Son herb&iacute;voros especializados que se alimentan de gram&iacute;neas y otras plantas de altura. Viven en grupos sociales bien estructurados: generalmente harenes (un macho, varias hembras y sus cr&iacute;as) que defienden un territorio, o grupos de machos solteros. Son diurnas y altamente vigilantes, utilizando sus sentidos agudos para detectar depredadores como el puma y el zorro andino. Su adaptaci&oacute;n a la hipoxia de la altura se refleja en su alta concentraci&oacute;n de hemoglobina en la sangre.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\"> La vicu&ntilde;a fue severamente cazada en el pasado por su preciada fibra, lo que la llev&oacute; al borde de la extinci&oacute;n. Gracias a estrictos programas de conservaci&oacute;n y manejo sostenible (como el \"chaku\" ancestral, una captura y esquila sin da&ntilde;o), sus poblaciones se han recuperado significativamente y actualmente est&aacute;n clasificadas como \"Preocupaci&oacute;n Menor\" a nivel global. Es un s&iacute;mbolo de la fauna andina y un pilar econ&oacute;mico para las comunidades locales que participan en su manejo, valorando su fibra sin sacrificar al animal.</span></p>\r\n</li>\r\n</ul>', 'especies/12.jpg', 'inactivo', '2025-10-31 13:53:21', '2025-11-19 09:37:50', 5, 5),
(13, 'Lagidium viscacia', 'lagidium-viscacia', 'Vizcacha de la Sierra', 6, '<p><span dir=\"auto\">Lagidium viscacia: La Vizcacha de la Sierra, Roedor Andino de Ambientes Rocosos</span></p>\r\n<p><span dir=\"auto\">La </span><span dir=\"auto\">Lagidium viscacia</span><span dir=\"auto\"> , conocida como vizcacha de la sierra, es un roedor end&eacute;mico de las zonas monta&ntilde;osas de Sudam&eacute;rica, particularmente de la Cordillera de los Andes. Es una especie caracter&iacute;stica de los ambientes rocosos de altura en Chile, donde se ha adaptado a vivir en climas fr&iacute;os y &aacute;ridos, utilizando las formaciones rocosas como refugio.</span></p>\r\n<p><strong><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul>\r\n<li>\r\n<p><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es un roedor de tama&ntilde;o mediano a grande, con una longitud cabeza-cuerpo de aproximadamente 30-50 cm y una cola larga y tupida (20-40 cm), que se curva hacia arriba y termina en un penacho de pelo negro. Su peso var&iacute;a entre 1,5 y 3 kg. Su pelaje es denso y suave, de color gris&aacute;ceo a pardusco en el dorso, que se aclara hacia el vientre. Posee orejas grandes y ojos oscuros y prominentes.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n en Chile:</span></strong><span dir=\"auto\"> En Chile, la vizcacha de la sierra se distribuye a lo largo de la Cordillera de los Andes, desde la Regi&oacute;n de Arica y Parinacota hasta la Regi&oacute;n de Ays&eacute;n, en altitudes que generalmente van desde los 800 hasta m&aacute;s de 5.000 metros sobre el nivel del mar. Habita exclusivamente en h&aacute;bitats rocosos, como afloramientos de roca, acantilados y campos de bloques, donde las grietas y cuevas les proporcionan refugio y protecci&oacute;n.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Son herb&iacute;voros diurnos, que se alimentan de una variedad de plantas xer&oacute;fitas (adaptadas a la secuencia), pastos, l&iacute;quenes y musgos. Viven en colonias en las rocas, donde construyen elaborados sistemas de galer&iacute;as y usan las formaciones rocosas para protegerse de depredadores como el puma, el zorro culpeo y aves rapaces. Son sociales y se les ve a menudo tomando el sol en grupos sobre las rocas. Su dieta y comportamiento de forrajeo los convierten en importantes consumidores primarios en los ecosistemas de altura.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\"> Las poblaciones de vizcacha de la sierra son consideradas estables en gran parte de su rango y est&aacute;n clasificadas como de \"Preocupaci&oacute;n Menor\" a nivel global. A pesar de la presi&oacute;n por la caza en algunas &aacute;reas y las alteraciones de su h&aacute;bitat, su capacidad para utilizar refugios rocosos les ha permitido persistir. Su presencia es un indicador clave de la salud de los ambientes andinos rocosos, y son una presa importante para los carn&iacute;voros nativos.</span></p>\r\n</li>\r\n</ul>', 'especies/13.jpg', 'inactivo', '2025-10-31 13:56:34', '2025-11-19 09:38:15', 5, 5),
(14, 'Lontra felina', 'lontra-felina', 'Nutria Marina', 6, '<p><span dir=\"auto\">Lontra felina: El Chungungo, Nutria Marina Exclusiva de las Costas del Pac&iacute;fico Sur</span></p>\r\n<p><span dir=\"auto\">La </span><span dir=\"auto\">Lontra felina</span><span dir=\"auto\"> , conocida como chungungo o nutria marina de Chile, es la nutria m&aacute;s peque&ntilde;a del mundo y el &uacute;nico mam&iacute;fero marino de su tipo que habita exclusivamente las costas rocosas del Pac&iacute;fico Sudamericano. Es una especie altamente especializada y un bioindicador crucial de la salud de los ecosistemas marinos costeros en Chile y Per&uacute;.</span></p>\r\n<p><strong><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul>\r\n<li>\r\n<p><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es la nutria m&aacute;s peque&ntilde;a (longitud cabeza-cuerpo de 57-79 cm; peso de 3-6 kg), con un cuerpo alargado y fusiforme, muy hidrodin&aacute;mico. Su pelaje es corto, denso y de color pardo oscuro en el dorso, con la parte ventral m&aacute;s clara, lo que le proporciona un excelente aislamiento t&eacute;rmico en el agua fr&iacute;a. Posee una cabeza ancha y aplanada, orejas peque&ntilde;as, ojos oscuros y vibrisas (bigotes) prominentes que usan para detectar presas. Sus patas son cortas y palmeadas, adaptadas para la nataci&oacute;n.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n en Chile:</span></strong><span dir=\"auto\"> En Chile, el chungungo se distribuye a lo largo de casi toda la costa, desde la Regi&oacute;n de Arica y Parinacota hasta el Cabo de Hornos en Magallanes. Habita exclusivamente en ambientes marinos costeros rocosos, incluyendo costas expuestas, archipi&eacute;lagos e islotes, donde encuentra refugio entre las rocas y acceso a abundantes fuentes de alimento.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Es un depredador carn&iacute;voro y bent&oacute;nico, aliment&aacute;ndose principalmente de crust&aacute;ceos (cangrejos, camarones), moluscos (locos, lapas, mejillones) y peces peque&ntilde;os, los cuales capturan con destreza en el fondo marino. Son animales solitarios o viven en peque&ntilde;os grupos familiares. Son seminocturnos o crepusculares, aunque pueden ser activos durante el d&iacute;a. Utilizan madrigueras o cuevas entre las rocas para descansar y criar a sus cr&iacute;as.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\"> El chungungo est&aacute; clasificado como \"En Peligro\" a nivel global y nacional en Chile, debido principalmente a la caza furtiva por su piel, la degradaci&oacute;n de su h&aacute;bitat costero (contaminaci&oacute;n, desarrollo urbano) y la interacci&oacute;n con pesquer&iacute;as. Su presencia es un excelente indicador de la buena calidad del agua y la riqueza de los ecosistemas marinos rocosos, lo que subraya la importancia de su conservaci&oacute;n para la salud costera del Pac&iacute;fico Sudamericano.</span></p>\r\n</li>\r\n</ul>', 'especies/14.jpg', 'inactivo', '2025-10-31 14:01:24', '2025-11-19 09:36:44', 5, 5),
(15, 'Pudu puda', 'pudu-puda', 'Pudú', 6, '<p><span dir=\"auto\">Pudu puda: El Pud&uacute; del Sur, El Ciervo M&aacute;s Peque&ntilde;o del Mundo en los Bosques Templados Chilenos</span></p>\r\n<p><span dir=\"auto\">La </span><span dir=\"auto\">Pudu puda</span><span dir=\"auto\"> , conocida como pud&uacute; del sur o pud&uacute; chileno, es el c&eacute;rvido m&aacute;s peque&ntilde;o del mundo, un animal ic&oacute;nico de los densos bosques templados y selvas valdivianas del sur de Chile y Argentina. Su tama&ntilde;o diminuto y comportamiento elusivo lo convierten en una especie fascinante y un indicador de la salud de los bosques nativos.</span></p>\r\n<p><strong><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul>\r\n<li>\r\n<p><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es el ciervo m&aacute;s peque&ntilde;o del mundo, con una altura a la cruz de solo 32-44 cm y un peso que oscila entre 6,5 y 13,5 kg. Su cuerpo es compacto y robusto, con patas cortas. Su pelaje es grueso y &aacute;spero, de color marr&oacute;n rojizo oscuro uniforme, que le proporciona camuflaje en el sotobosque. Los machos poseen astas cortas y simples (sin ramificaciones), de no m&aacute;s de 10 cm de largo, que se renuevan anualmente. Tiene orejas redondeadas y grandes ojos oscuros.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n en Chile:</span></strong><span dir=\"auto\"> En Chile, el pud&uacute; del sur se distribuye desde la Regi&oacute;n del Maule hasta la Regi&oacute;n de Ays&eacute;n, incluyendo Chilo&eacute; y otras islas. Habita exclusivamente en los densos bosques templados lluviosos, conocidos como selva valdiviana, y en matorrales densos, donde la vegetaci&oacute;n baja y la abundante hojarasca le brindan alimento y refugio.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Es un herb&iacute;voro solitario y de h&aacute;bitos principalmente crepusculares y nocturnos, aunque puede observarse durante el d&iacute;a en zonas tranquilas. Su dieta consiste en brotes tiernos, hojas de arbustos, helechos, musgos, l&iacute;quenes y frutos ca&iacute;dos. Es un animal muy t&iacute;mido y huidizo, que utiliza su peque&ntilde;o tama&ntilde;o y la densidad de la vegetaci&oacute;n para esconderse de depredadores como el puma, el zorro culpeo y perros asilvestrados. Al sentirse amenazado, puede huir en zig-zag o permanecer inm&oacute;vil y camuflado.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\"> El pud&uacute; del sur est&aacute; clasificado como \"Vulnerable\" a nivel global y nacional. Sus principales amenazas son la p&eacute;rdida y fragmentaci&oacute;n de su h&aacute;bitat debido a la deforestaci&oacute;n y la urbanizaci&oacute;n, la depredaci&oacute;n por perros asilvestrados y la caza furtiva. Como herb&iacute;voro del sotobosque, juega un papel en la dispersi&oacute;n de semillas y en la estructuraci&oacute;n de la vegetaci&oacute;n. Su conservaci&oacute;n es crucial para la biodiversidad de los bosques templados de Sudam&eacute;rica.</span></p>\r\n</li>\r\n</ul>', 'especies/15.jpg', 'inactivo', '2025-10-31 14:07:12', '2025-11-19 09:37:01', 5, 5);
INSERT INTO `especies` (`esp_id`, `esp_nombre_cientifico`, `esp_slug`, `esp_nombre_comun`, `esp_tax_id`, `esp_descripcion`, `esp_imagen`, `esp_estado`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(16, 'Oryctolagus cuniculus:', 'oryctolagus-cuniculus', 'Conejo Europeo', 6, '<p><span dir=\"auto\">Oryctolagus cuniculus: El Conejo Europeo, Roedor de Adaptabilidad Global y Rol Ecol&oacute;gico Dual</span></p>\r\n<p><span dir=\"auto\">La </span><span dir=\"auto\">Oryctolagus cuniculus</span><span dir=\"auto\"> , com&uacute;nmente conocido como conejo europeo o conejo com&uacute;n, es un lagomorfo originario de la Pen&iacute;nsula Ib&eacute;rica que ha sido introducido en casi todos los continentes, convirti&eacute;ndose en una de las especies de mam&iacute;feros m&aacute;s ampliamente distribuidas y un ejemplo de &eacute;xito adaptativo, aunque tambi&eacute;n un agente de impacto ambiental.</span></p>\r\n<p><strong><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul>\r\n<li>\r\n<p><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es un lagomorfo de tama&ntilde;o mediano (longitud cabeza-cuerpo de 35-45 cm; peso de 1,5-2,5 kg). Su pelaje es generalmente de color gris parduzco, con el vientre m&aacute;s claro y una mancha rojiza en la nuca. Posee orejas largas (pero m&aacute;s cortas que las de las liebres) y ojos grandes. Sus patas traseras son m&aacute;s largas y fuertes que las delanteras, adaptadas para correr y cavar. A diferencia de los roedores, los lagomorfos tienen cuatro incisivos en la mand&iacute;bula superior, dos de los cuales son peque&ntilde;os y est&aacute;n detr&aacute;s de los dos frontales.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n:</span></strong><span dir=\"auto\"> Originario de Espa&ntilde;a y Portugal, el conejo europeo ha sido introducido por el ser humano en todo el mundo, encontr&aacute;ndose hoy en d&iacute;a en Europa, &Aacute;frica, Ocean&iacute;a, Norteam&eacute;rica y Sudam&eacute;rica, incluyendo Chile. Habita una amplia variedad de ecosistemas, como pastizales, matorralales, bosques abiertos, dunas costeras, &aacute;reas agr&iacute;colas y suburbanas, siempre que haya suelos aptos para excavar madrigueras y vegetaci&oacute;n para alimentarse.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Son herb&iacute;voros que se alimentan de una gran diversidad de vegetaci&oacute;n, incluyendo pastos, hierbas, brotes, cortezas y ra&iacute;ces. Son gregarios, viven en grandes colonias y excavan complejos sistemas de madrigueras subterr&aacute;neas llamados conejeras, que les proporcionan refugio y protecci&oacute;n. Son principalmente crepusculares y nocturnos, aunque pueden observarse durante el d&iacute;a. Su alta tasa reproductiva es una de las claves de su &eacute;xito.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\"> En su rango nativo original, el conejo europeo est&aacute; clasificado como \"Casi Amenazado\" debido a enfermedades como la mixomatosis y la enfermedad hemorr&aacute;gica viral. Sin embargo, en muchos de los lugares donde ha sido introducido, se considera una especie invasora, causando graves da&ntilde;os ecol&oacute;gicos (competencia con fauna nativa, sobrepastoreo, erosi&oacute;n del suelo) y econ&oacute;micos (da&ntilde;os a cultivos). En Chile, es una especie introducida y com&uacute;n en muchos ambientes, ejerciendo ambos roles: fuente de alimento para depredadores nativos y, en altas densidades, un agente de impacto ambiental negativo.</span></p>\r\n</li>\r\n</ul>', 'especies/16.jpg', 'activo', '2025-10-31 14:16:01', '2025-10-31 14:16:01', 5, 5),
(17, 'Puma concolor', 'puma-concolor', 'Puma', 6, '<p><em><span dir=\"auto\">Puma concolor</span></em><span dir=\"auto\"> (puma o le&oacute;n de monta&ntilde;a) y una imagen realista, sin marco, adecuada para una tesis.</span></p>\r\n<hr>\r\n<p><span dir=\"auto\">Puma concolor: El Puma, El Gran Felino Solitario de Am&eacute;rica</span></p>\r\n<p><span dir=\"auto\">La </span><span dir=\"auto\">Puma concolor</span><span dir=\"auto\"> , conocido como puma, le&oacute;n de monta&ntilde;a o puma, es el felino de mayor distribuci&oacute;n en todo el continente americano. Es un &aacute;pice depredador, un s&iacute;mbolo de la vida silvestre y un indicador de la salud de los ecosistemas, presente en una vasta gama de h&aacute;bitats en Chile, desde el desierto hasta la Patagonia.</span></p>\r\n<p><strong><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul>\r\n<li>\r\n<p><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es un felino grande y musculoso (longitud cabeza-cuerpo de 1,5 a 2,7 metros, incluyendo la cola; peso de 30 a 100 kg, con machos significativamente m&aacute;s grandes que las hembras). Su pelaje es uniforme, sin manchas, generalmente de color pardo-amarillento, gris&aacute;ceo o rojizo, con el vientre m&aacute;s claro y el hocico, el reverso de las orejas y la punta de la cola m&aacute;s oscura. Posee una cabeza relativamente peque&ntilde;a, ojos grandes y una cola larga y musculosa que utiliza para el equilibrio.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n en Chile:</span></strong><span dir=\"auto\"> En Chile, el puma se distribuye pr&aacute;cticamente a lo largo de todo el territorio continental, desde la Regi&oacute;n de Arica y Parinacota hasta la Regi&oacute;n de Magallanes. Habita en una asombrosa diversidad de ecosistemas, incluyendo desiertos, estepas altoandinas, matorrales, bosques templados, selvas valdivianas y estepas patag&oacute;nicas. Es una especie altamente adaptable a diferentes climas y vegetaciones.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Es un depredador carn&iacute;voro solitario y muy territorial, cazando principalmente mam&iacute;feros medianos y grandes. Su dieta principal incluye ungulados como guanacos, huemules y ciervos, pero tambi&eacute;n consume roedores, conejos, aves y, ocasionalmente, ganado. Son cazadores sigilosos y emboscadores, con h&aacute;bitos crepusculares y nocturnos. El puma juega un papel ecol&oacute;gico fundamental en la regulaci&oacute;n de las poblaciones de herb&iacute;voros, contribuyendo al equilibrio de los ecosistemas.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\"> Aunque sus poblaciones han disminuido en algunas &aacute;reas debido a la p&eacute;rdida de h&aacute;bitat, la fragmentaci&oacute;n y la persecuci&oacute;n por conflictos con la ganader&iacute;a, el puma est&aacute; clasificado como \"Preocupaci&oacute;n Menor\" a nivel global. En Chile, es una especie protegida, aunque los conflictos con los crianceros persisten. Su presencia es un indicador clave de la salud y la integridad ecol&oacute;gica de los grandes ecosistemas, siendo un depredador vital para la din&aacute;mica de la vida silvestre.</span></p>\r\n</li>\r\n</ul>', 'especies/17.jpg', 'inactivo', '2025-10-31 14:22:45', '2025-11-19 09:39:07', 5, 5),
(18, 'Canis familiaris', 'canis-familiaris', 'Kiltro', 6, '<p><span dir=\"auto\">Canis familiaris: El Perro Dom&eacute;stico, Compa&ntilde;ero Milenario y Agente de Impacto Antr&oacute;pico</span></p>\r\n<p><span dir=\"auto\">La Canis familiaris</span><strong class=\"\"> </strong><span dir=\"auto\">, </span><span dir=\"auto\">com&uacute;n conocido como perro dom&eacute;stico, </span><span dir=\"auto\">es una subespecie domesticada del lobo ( </span><em class=\"\"><span dir=\"auto\">Canis lupus </span></em><span dir=\"auto\">) y el primer animal en ser domesticado por el ser humano. </span><span dir=\"auto\">Presente en pr&aacute;cticamente todos los rincones del planeta, </span><span dir=\"auto\">su relaci&oacute;n con el hombre es milenaria, </span><span dir=\"auto\">pero su presencia, </span><span dir=\"auto\">especialmente en estado asilvestrado o sin control, </span><span dir=\"auto\">genera importantes desaf&iacute;os ecol&oacute;gicos y sanitarios.</span></p>\r\n<p><strong class=\"\"><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul>\r\n<li>\r\n<p><strong class=\"\"><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Extremadamente variable debido a la selecci&oacute;n artificial. </span><span dir=\"auto\">La longitud, </span><span dir=\"auto\">el peso y el pelaje var&iacute;an enormemente entre razas y mestizos, </span><span dir=\"auto\">desde peque&ntilde;os chihuahuas hasta grandes gran daneses. </span><span dir=\"auto\">Comparten caracter&iacute;sticas generales de los c&aacute;nidos: </span><span dir=\"auto\">cuatro patas, </span><span dir=\"auto\">cola, </span><span dir=\"auto\">hocico, </span><span dir=\"auto\">y sentidos del o&iacute;do y olfato muy desarrollados.</span></p>\r\n</li>\r\n<li>\r\n<p><strong class=\"\"><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n:</span></strong><span dir=\"auto\"> Globalmente distribuido. </span><span dir=\"auto\">Su h&aacute;bitat principal son los entornos humanos (domicilios, </span><span dir=\"auto\">ciudades, </span><span dir=\"auto\">zonas rurales). </span><span dir=\"auto\">Sin embargo, </span><span dir=\"auto\">poblaciones de perros asilvestrados o ferales ocupan una diversidad de ecosistemas naturales y seminaturales en todos los continentes, </span><span dir=\"auto\">incluido Chile, </span><span dir=\"auto\">adapt&aacute;ndose a desiertos, </span><span dir=\"auto\">bosques, </span><span dir=\"auto\">monta&ntilde;as y estepas.</span></p>\r\n</li>\r\n<li>\r\n<p><strong class=\"\"><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Si bien los perros dom&eacute;sticos dependen de los humanos para su subsistencia, </span><span dir=\"auto\">las poblaciones asilvestradas o con acceso a ambientes naturales exhiben comportamientos de depredaci&oacute;n. </span><span dir=\"auto\">Son omn&iacute;voros, </span><span dir=\"auto\">aliment&aacute;ndose de carro&ntilde;a, </span><span dir=\"auto\">residuos humanos, </span><span dir=\"auto\">pero tambi&eacute;n cazando fauna silvestre (mam&iacute;feros peque&ntilde;os y medianos, </span><span dir=\"auto\">aves, </span><span dir=\"auto\">reptiles, </span><span dir=\"auto\">insectos). </span><span dir=\"auto\">Pueden formar jaur&iacute;as con estructuras sociales complejas. </span><span dir=\"auto\">Su comportamiento reproductivo es prol&iacute;fico y su alta densidad poblacional es una preocupaci&oacute;n.</span></p>\r\n</li>\r\n<li>\r\n<p><strong class=\"\"><span dir=\"auto\">Relevancia y Conflictos en Ecosistemas Naturales (Chile): </span></strong><span dir=\"auto\">En Chile, </span><span dir=\"auto\">los perros asilvestrados o con tenencia irresponsable representan una seria amenaza para la fauna silvestre nativa, </span><span dir=\"auto\">incluyendo especies vulnerables como el pud&uacute;, </span><span dir=\"auto\">el zorro culpeo, </span><span dir=\"auto\">el zorro chilla, </span><span dir=\"auto\">el guanaco y diversas aves. </span><span dir=\"auto\">Su depredaci&oacute;n, </span><span dir=\"auto\">la transmisi&oacute;n de enfermedades (ej.</span><span dir=\"auto\">moquillo canino) y la competencia por recursos y espacios generan graves impactos en la biodiversidad.</span><span dir=\"auto\">Adem&aacute;s,</span><span dir=\"auto\">pueden tener un impacto negativo en la ganader&iacute;a.</span><span dir=\"auto\">Su gesti&oacute;n y el fomento de la tenencia responsable son desaf&iacute;os cr&iacute;ticos</span></p>\r\n</li>\r\n</ul>', 'especies/18.jpg', 'inactivo', '2025-10-31 14:31:17', '2025-11-19 09:38:32', 5, 5),
(19, 'Spalacopus cyanus', 'spalacopus-cyanus', 'El cururo', 6, '<p><span dir=\"auto\">Spalacopus cyanus: El Cururo, Roedor Fosilorial End&eacute;mico de Chile</span></p>\r\n<p><span dir=\"auto\">La </span><span dir=\"auto\">Spalacopus cyanus</span><span dir=\"auto\"> , com&uacute;nmente conocido como cururo, es un roedor fosorial (que vive bajo tierra) end&eacute;mico de Chile, y la &uacute;nica especie del g&eacute;nero </span><em><span dir=\"auto\">Spalacopus</span></em><span dir=\"auto\"> . Es un animal altamente especializado y un ingeniero de ecosistemas subterr&aacute;neos, cuya presencia es crucial para la din&aacute;mica de los suelos y la vegetaci&oacute;n en los ambientes donde habita.</span></p>\r\n<p><strong><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul>\r\n<li>\r\n<p><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es un roedor de tama&ntilde;o mediano (longitud cabeza-cuerpo de 11-16 cm; peso de 60-150 g), con un cuerpo cil&iacute;ndrico y robusto, perfectamente adaptado a la vida subterr&aacute;nea. Su pelaje es corto, denso y de coloraci&oacute;n variable, generalmente negro azulado brillante (\"cyanus\" significa azul oscuro), pero puede tener tonos pardos o grises. Posee una cabeza grande, ojos muy peque&ntilde;os y casi ocultos, orejas vestigiales y un hocico corto. Sus patas delanteras son fuertes, con garras robustas y adaptadas para excavar, y su cola es corta y casi lampi&ntilde;a.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n en Chile:</span></strong><span dir=\"auto\"> El cururo se distribuye ampliamente en Chile Central, desde la Regi&oacute;n de Atacama hasta la Regi&oacute;n del Biob&iacute;o, con poblaciones aisladas m&aacute;s al sur. Habita en una diversidad de ambientes con suelos blandos y profundos, como matorrales, pastizales, dunas costeras y zonas agr&iacute;colas. Su presencia est&aacute; directamente ligada a la disponibilidad de suelos adecuados para sus extensos sistemas de galer&iacute;as subterr&aacute;neas.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Es un roedor estrictamente subterr&aacute;neo, excavando complejos sistemas de t&uacute;neles por donde pasa la mayor parte de su vida. Son herb&iacute;voros, aliment&aacute;ndose principalmente de ra&iacute;ces, bulbos y tub&eacute;rculos que se encuentran bajo tierra. A diferencia de muchos otros roedores fosoriales, el cururo es social, viviendo en colonias que pueden incluir varios individuos en un mismo sistema de madrigueras. Son activos durante el d&iacute;a, con salidas espor&aacute;dicas a la superficie, principalmente para recolectar vegetaci&oacute;n. Sus vocalizaciones, que incluyen un distintivo \"cur&uacute;\", son importantes para la comunicaci&oacute;n dentro de la colonia.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\"> A nivel nacional, el cururo est&aacute; clasificado como \"Preocupaci&oacute;n Menor\", aunque algunas poblaciones aisladas o subespecies pueden enfrentar mayores amenazas. Sus principales amenazas son las alteraciones y p&eacute;rdida de h&aacute;bitat por la agricultura, la urbanizaci&oacute;n y la erosi&oacute;n del suelo. Juega un rol ecol&oacute;gico fundamental como ingeniero de ecosistemas: sus excavaciones airean el suelo, aumentan la infiltraci&oacute;n de agua, reciclan nutrientes y modifican la estructura de la vegetaci&oacute;n, beneficiando a otras especies ya la salud general del ecosistema.</span></p>\r\n</li>\r\n</ul>', 'especies/19.jpg', 'inactivo', '2025-10-31 14:35:50', '2025-11-19 09:38:54', 5, 5),
(20, 'Ovis Aries', 'ovis-aries', 'Oveja doméstica', 6, '<p><strong><span dir=\"auto\">Ovis aries: La Oveja Dom&eacute;stica, Rumiante de Profundo Impacto Socioecon&oacute;mico y Ecol&oacute;gico</span></strong></p>\r\n<p><span dir=\"auto\">La </span><strong><span dir=\"auto\">Ovis aries</span></strong><span dir=\"auto\"> , conocida como oveja dom&eacute;stica, es un mam&iacute;fero rumiante, especie clave en la historia de la ganader&iacute;a y la civilizaci&oacute;n humana. Domesticada hace millas de a&ntilde;os a partir del mufl&oacute;n silvestre ( </span><em><span dir=\"auto\">Ovis orientalis</span></em><span dir=\"auto\"> ), su presencia global y su rol en la econom&iacute;a y la cultura son innegables, aunque su manejo tambi&eacute;n genera importantes consideraciones ecol&oacute;gicas.</span></p>\r\n<p><strong><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul>\r\n<li>\r\n<p><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Var&iacute;a enormemente seg&uacute;n la raza, pero generalmente presentan un cuerpo robusto cubierto de lana, que puede ser de diversos colores (blanco, negro, marr&oacute;n, gris). Los machos (carneros) suelen ser m&aacute;s grandes que las hembras (ovejas), pudiendo tener cuernos grandes y en espiral, mientras que las hembras pueden ser bellotas o tener cuernos m&aacute;s peque&ntilde;os. Tienen pezu&ntilde;as hendidas y una dentadura adaptada para el pastoreo.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n:</span></strong><span dir=\"auto\"> Globalmente distribuidas, las ovejas se cr&iacute;an en casi todos los ecosistemas terrestres donde el pastoreo es posible, desde praderas templadas hasta zonas &aacute;ridas y monta&ntilde;as. En Chile, la ganader&iacute;a ovina tiene una gran relevancia en zonas como la Patagonia, Ays&eacute;n y el altiplano, adapt&aacute;ndose a las condiciones locales.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Son herb&iacute;voros que se alimentan principalmente de pastos, hierbas y, en menor medida, de arbustos. Son animales gregarios, que viven en reba&ntilde;os con una marcada jerarqu&iacute;a social. Su comportamiento de pastoreo es intensivo y puede afectar significativamente la estructura de la vegetaci&oacute;n. Han sido seleccionadas para producir lana, carne y leche, y su ciclo reproductivo es bien conocido.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Impacto y Relevancia (Chile):</span></strong><span dir=\"auto\"> En Chile, la oveja ha sido un pilar de la econom&iacute;a rural, especialmente en el sur, donde la producci&oacute;n de lana y carne es tradicional. Sin embargo, su presencia masiva en ecosistemas fr&aacute;giles, particularmente en la Patagonia, puede generar impactos ecol&oacute;gicos importantes:</span></p>\r\n<ul>\r\n<li>\r\n<p><strong><span dir=\"auto\">Sobrepastoreo y Erosi&oacute;n:</span></strong><span dir=\"auto\"> El pastoreo intensivo puede llevar a la degradaci&oacute;n de los pastizales nativos, la p&eacute;rdida de biodiversidad vegetal y la erosi&oacute;n del suelo, especialmente en &aacute;reas semi&aacute;ridas o con poca resiliencia.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Competencia:</span></strong><span dir=\"auto\"> Compiten con herb&iacute;voros nativos como el guanaco y el huemul por recursos alimenticios, lo que puede afectar a las poblaciones de fauna silvestre.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Alteraci&oacute;n de H&aacute;bitat:</span></strong><span dir=\"auto\"> La infraestructura asociada a la ganader&iacute;a (cercos, aguadas) puede fragmentar h&aacute;bitats naturales y alterar patrones de movimiento de la fauna. A pesar de estos desaf&iacute;os, la ganader&iacute;a ovina sigue siendo una actividad cultural y econ&oacute;mica vital, y la b&uacute;squeda de pr&aacute;cticas de manejo sostenible es crucial para mitigar su impacto ambiental y asegurar la coexistencia con la fauna nativa.</span></p>\r\n</li>\r\n</ul>\r\n</li>\r\n</ul>', 'especies/20.jpg', 'activo', '2025-11-02 22:20:05', '2025-11-02 22:20:05', 5, 5),
(21, 'Caracara plancus', 'caracara-plancus', 'Traro', 4, '<p><strong><span dir=\"auto\">Caracara plancus: El Traro (Caracara Com&uacute;n), Rapaz Carro&ntilde;era y Oportunista de Sudam&eacute;rica</span></strong></p>\r\n<p><span dir=\"auto\">La </span><strong><span dir=\"auto\">Caracara plancus</span></strong><span dir=\"auto\"><strong> o Carancho</strong>, conocida como traro o caracara com&uacute;n, es una rapaz diurna de la familia Falconidae, ampliamente distribuida en Am&eacute;rica. Es un ave emblem&aacute;tica de los paisajes abiertos de Chile, destacando por su comportamiento oportunista y su dieta variada, que incluye carro&ntilde;a, siendo un importante limpiador de los ecosistemas.</span></p>\r\n<p><strong><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul>\r\n<li>\r\n<p><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es un ave rapaz grande y robusta (longitud de 50-65 cm; envergadura de 120-130 cm), con un aspecto distintivo. Su plumaje es predominantemente negro en la cabeza, cuello, espalda y pecho, con el vientre, la rabadilla y la base de la cola blancas, y finas barras negras en el abdomen y muslos. La caracter&iacute;stica m&aacute;s distintiva es su cara, que es desnuda y de color rojo brillante o anaranjado, que puede variar de intensidad seg&uacute;n el estado de &aacute;nimo o la &eacute;poca reproductiva. Su pico es fuerte y ganchudo.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n en Chile:</span></strong><span dir=\"auto\"> En Chile, el traro se distribuye ampliamente desde el desierto de Atacama hasta Tierra del Fuego. Habita en una gran variedad de ambientes abiertos y semiabiertos, incluyendo estepas, praderas, zonas agr&iacute;colas, matorrales, bordes de bosques y &aacute;reas costeras. Es com&uacute;n observarlo en zonas de ganader&iacute;a, cerca de caminos o en basurales.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Es un depredador y carro&ntilde;ero altamente oportunista. Su dieta es muy diversa, incluyendo carro&ntilde;a de mam&iacute;feros, peque&ntilde;os mam&iacute;feros (roedores, lagomorfos), aves, reptiles, insectos, huevos y peces. A menudo se le ve caminando por el suelo en busca de alimento o esperando junto a otros carro&ntilde;eros. Es un ave inteligente y curiosa, que no teme acercarse a asentamientos humanos. Construye nidos voluminosos en &aacute;rboles o acantilados y puedes vivir en solitario o en peque&ntilde;os grupos familiares.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\"> El traro est&aacute; clasificado como \"Preocupaci&oacute;n Menor\" a nivel global y en Chile, debido a su amplia distribuci&oacute;n y poblaciones estables. Juega un rol ecol&oacute;gico fundamental como carro&ntilde;ero, limpiando el ambiente de animales muertos y contribuyendo al ciclo de nutrientes. Su adaptabilidad a paisajes modificados por el hombre lo convierte en una especie resiliente y un componente visible de la fauna chilena.</span></p>\r\n</li>\r\n</ul>', 'especies/21.jpg', 'activo', '2025-11-03 19:48:50', '2025-11-03 19:54:05', 5, 5),
(22, 'Campephilus magellanicus', 'campephilus-magellanicus', 'Pájaro Carpintero Negro', 4, '<p><span dir=\"auto\">Campephilus magellanicus: El P&aacute;jaro Carpintero Negro, Joya Alada de los Bosques Templados Chilenos</span></p>\r\n<p><span dir=\"auto\">La </span><span dir=\"auto\">Campephilus magellanicus</span><span dir=\"auto\"> , conocida como p&aacute;jaro carpintero negro o carpintero de Magallanes, es el carpintero m&aacute;s grande de Sudam&eacute;rica y una especie ic&oacute;nica de los densos bosques templados lluviosos de Chile y Argentina. Su presencia y comportamiento son indicadores clave de la salud y madurez de los ecosistemas forestales nativos.</span></p>\r\n<p><strong><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul>\r\n<li>\r\n<p><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es un ave grande y vistosa (longitud de 36-38 cm; peso de 270-340 g). El plumaje de la hembra es completamente negro brillante, con un caracter&iacute;stico copete o cresta negra curvada hacia atr&aacute;s. El macho es igualmente negro, pero con la cabeza y el copete de un llamativo color rojo carmes&iacute; brillante, lo que lo hace inconfundible. Ambos sexos tienen un pico fuerte y cuneiforme, de color gris&aacute;ceo, y patas zigod&aacute;ctilas (dos dedos adelante hacia y dos hacia atr&aacute;s) que les permiten trepar verticalmente por los troncos.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n en Chile:</span></strong><span dir=\"auto\"> En Chile, el p&aacute;jaro carpintero negro se distribuye desde la Regi&oacute;n de O\'Higgins hasta la Regi&oacute;n de Magallanes, incluyendo el archipi&eacute;lago de Chilo&eacute; y Tierra del Fuego. Habita en bosques templados maduros de Nothofagus (robles, coihues, lengas) y otras especies arb&oacute;reas nativas, donde se encuentran &aacute;rboles grandes y viejos con madera muerta, esenciales para su alimentaci&oacute;n y nidificaci&oacute;n.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Es un ave principalmente insect&iacute;vora, aliment&aacute;ndose de larvas de escarabajos y otros insectos xil&oacute;fagos que extrae de la madera en elaboraci&oacute;n mediante potentes golpes de su pico. Su acci&oacute;n de \"picotear\" crea grandes orificios rectangulares en los troncos. Tambi&eacute;n consume frutos, semillas e invertebrados en la superficie. Son aves territoriales y suelen vivir en parejas o peque&ntilde;os grupos familiares. Su llamado es un distintivo \"kew-kew-kew\" y el sonido de sus picotazos es audible a distancia.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\"> A nivel global y nacional, el p&aacute;jaro carpintero negro est&aacute; clasificado como \"Preocupaci&oacute;n Menor\". Sin embargo, su dependencia de los bosques maduros lo hace vulnerable a la tala y fragmentaci&oacute;n de su h&aacute;bitat. Juega un rol ecol&oacute;gico vital como \"ingeniero de ecosistemas\": sus agujeros en los troncos no solo le permiten alimentarse, sino que tambi&eacute;n crean cavidades que son utilizadas por otras especies de aves y mam&iacute;feros para anidar o refugiarse, contribuyendo a la biodiversidad forestal.</span></p>\r\n</li>\r\n</ul>', 'especies/22.jpg', 'activo', '2025-11-03 19:51:54', '2025-11-19 23:42:00', 5, 5),
(23, 'Sephanoides fernandensis:', 'sephanoides-fernandensis', 'Picaflor de Juan Fernández', 4, '<p><strong><span dir=\"auto\">El Picaflor de Juan Fern&aacute;ndez, Joya Alada End&eacute;mica de un Archipi&eacute;lago &Uacute;nico</span></strong></p>\r\n<p><span dir=\"auto\">La </span><strong><span dir=\"auto\">Sephanoides fernandensis</span></strong><span dir=\"auto\"> , com&uacute;nmente conocida como picaflor de Juan Fern&aacute;ndez o colibr&iacute; de Juan Fern&aacute;ndez, es una especie de colibr&iacute; end&eacute;mica del archipi&eacute;lago de Juan Fern&aacute;ndez, un sitio declarado Reserva Mundial de la Biosfera. Este picaflor es un tesoro de la biodiversidad chilena, notable por su marcado dimorfismo sexual y por ser una de las especies de aves m&aacute;s amenazadas del pa&iacute;s.</span></p>\r\n<p><strong><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul>\r\n<li>\r\n<p><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es un colibr&iacute; de tama&ntilde;o mediano (longitud de 11-13 cm, incluyendo el pico). Presenta un espectacular dimorfismo sexual:</span></p>\r\n<ul>\r\n<li>\r\n<p><strong><span dir=\"auto\">Macho:</span></strong><span dir=\"auto\"> Es de un color anaranjado-rojizo brillante en casi todo el cuerpo, con un copete iridiscente de color amarillo dorado o anaranjado intenso que brilla bajo la luz. Sus alas y cola son m&aacute;s oscuras.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Hembra:</span></strong><span dir=\"auto\"> Es de color verde azulado met&aacute;lico en el dorso, con el vientre blanco puro y llamativas manchas rojas o iridiscentes en la garganta. Su cola es verde con la punta blanca.</span></p>\r\n</li>\r\n</ul>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n en Chile:</span></strong><span dir=\"auto\"> El picaflor de Juan Fern&aacute;ndez es estrictamente end&eacute;mico del archipi&eacute;lago de Juan Fern&aacute;ndez. Hist&oacute;ricamente, habitaba las islas de Robinson Crusoe y Alejandro Selkirk, pero actualmente solo se encuentra en la isla Robinson Crusoe. Vive en los bosques nativos y matorrales densos del archipi&eacute;lago, donde la flora end&eacute;mica le proporciona alimento (n&eacute;ctar de flores) y sitios de nidificaci&oacute;n.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Es un nectar&iacute;voro, aliment&aacute;ndose principalmente del n&eacute;ctar de las flores de la flora nativa del archipi&eacute;lago, actuando como un importante polinizador. Complementa su dieta con peque&ntilde;os insectos y ara&ntilde;as. Son aves solitarias y territoriales, especialmente los machos, que defienden sus &aacute;reas de alimentaci&oacute;n. Su vuelo es &aacute;gil y r&aacute;pido, caracter&iacute;stico de los colibr&iacute;es.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\"> El picaflor de Juan Fern&aacute;ndez est&aacute; clasificado como \"En Peligro Cr&iacute;tico\" a nivel global y nacional. Es una de las aves m&aacute;s amenazadas de Chile, y su situaci&oacute;n es extremadamente precaria. Sus principales amenazas incluyen:</span></p>\r\n<ul>\r\n<li>\r\n<p><strong><span dir=\"auto\">P&eacute;rdida y Degradaci&oacute;n de H&aacute;bitat:</span></strong><span dir=\"auto\"> La introducci&oacute;n de especies vegetales ex&oacute;ticos (eg, mora, maqui) que compiten con la flora nativa, la deforestaci&oacute;n hist&oacute;rica y los incendios.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Depredaci&oacute;n por Especies Introducidas:</span></strong><span dir=\"auto\"> Gatos asilvestrados, ratas y cabras son depredadores y competidores significativos.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Fragmentaci&oacute;n de Poblaciones:</span></strong><span dir=\"auto\"> La poblaci&oacute;n actual es muy peque&ntilde;a y est&aacute; confinada a una sola isla. Su singularidad evolutiva y su rol como polinizador de la flora end&eacute;mica del archipi&eacute;lago lo convierten en una especie prioritaria para la conservaci&oacute;n de la biodiversidad de las islas oce&aacute;nicas de Chile.</span></p>\r\n</li>\r\n</ul>\r\n</li>\r\n</ul>', 'especies/23.png', 'activo', '2025-11-03 19:57:24', '2025-11-03 19:57:24', 5, 5),
(24, 'Vultur gryphus', 'vultur-gryphus', 'Cóndor Andino', 4, '<p><strong><span dir=\"auto\">Vultur gryphus: El C&oacute;ndor Andino, Majestuoso Rey de los Andes y S&iacute;mbolo de Am&eacute;rica</span></strong></p>\r\n<p><span dir=\"auto\">La </span><span dir=\"auto\">Vultur gryphus</span><span dir=\"auto\"> , com&uacute;nmente conocido como c&oacute;ndor andino, es el ave rapaz voladora terrestre m&aacute;s grande del mundo y un &iacute;cono de la Cordillera de los Andes, presente en el escudo nacional de Chile y de varios pa&iacute;ses sudamericanos. Es un carro&ntilde;ero esencial y una centinela de la salud de los ecosistemas de alta monta&ntilde;a.</span></p>\r\n<p><strong><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul>\r\n<li>\r\n<p><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es una ave de tama&ntilde;o colosal (longitud de 1,1 a 1,3 metros; envergadura de hasta 3,3 metros; peso de 9 a 15 kg). Su plumaje es predominantemente negro brillante, con un notorio \"collar\" de plumas blancas alrededor de la base del cuello y grandes parches blancos en la parte superior de las alas. La cabeza y el cuello son desprovistos de plumas, de color rojizo a rosado, lo que es una adaptaci&oacute;n para la higiene al alimentarse de carro&ntilde;a. Los machos poseen una gran cresta carnosa en la cabeza.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n en Chile:</span></strong><span dir=\"auto\"> En Chile, el c&oacute;ndor andino se distribuye a lo largo de toda la Cordillera de los Andes, desde la Regi&oacute;n de Arica y Parinacota hasta Tierra del Fuego. Habita en ambientes monta&ntilde;osos, con preferencia por zonas de altura (superiores a 2.000 metros), donde utiliza los vientos ascendentes para planear. Tambi&eacute;n se aventura en zonas costeras en busca de alimento.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Es un carro&ntilde;ero obligado, aliment&aacute;ndose casi exclusivamente de cad&aacute;veres de grandes mam&iacute;feros (guanacos, vicu&ntilde;as, ganado dom&eacute;stico) y, ocasionalmente, de mam&iacute;feros marinos varados. Juega un papel ecol&oacute;gico fundamental como limpiador del ecosistema, evitando la propagaci&oacute;n de enfermedades. Son aves gregarias, especialmente en los sitios de alimentaci&oacute;n y de pernoctaci&oacute;n comunales (\"dormideros\"). Nidifican en salientes rocosos inaccesibles y tienen una baja tasa reproductiva (un huevo cada dos a&ntilde;os). Son mon&oacute;gamos.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\"> El c&oacute;ndor andino est&aacute; clasificado como \"Vulnerable\" a nivel global y \"Casi Amenazado\" o \"Vulnerable\" en varias regiones de Chile. Sus principales amenazas incluyen la intoxicaci&oacute;n por ingesta de cebos envenenados (dirigidos a pumas o zorros), la colisi&oacute;n con tendidos el&eacute;ctricos, la persecuci&oacute;n directa (caza), las alteraciones de su h&aacute;bitat y la disminuci&oacute;n de su fuente de alimento natural. Su larga vida y baja tasa reproductiva los hacen particularmente sensibles a estas amenazas. Como ave carro&ntilde;era, es un componente vital para la salud de los ecosistemas andinos, reciclando nutrientes y evitando la acumulaci&oacute;n de cad&aacute;veres.</span></p>\r\n</li>\r\n</ul>', 'especies/24.png', 'activo', '2025-11-03 20:00:40', '2025-11-03 20:00:40', 5, 5),
(25, 'Leistes loyca', 'leistes-loyca', 'Loica', 4, '<p><strong><span dir=\"auto\">Leistes loyca: La Loica, Ave de Campo de Vistoso Pecho Rojo y Melodioso Canto Chileno</span></strong></p>\r\n<p><span dir=\"auto\">La </span><strong><span dir=\"auto\">Leistes loyca</span></strong><span dir=\"auto\"> , conocida como loica, es un ave paseriforme de la familia Icteridae, ampliamente distribuida en Sudam&eacute;rica. Es una de las aves m&aacute;s reconocibles de los campos y praderas de Chile, destacada por el llamativo color rojo de su pecho y su hermoso y sonoro canto.</span></p>\r\n<p><strong><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul>\r\n<li>\r\n<p><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es una ave de tama&ntilde;o mediano (longitud de 25-28 cm). El plumaje es predominantemente pardo oscuro o negruzco en el dorso, con rayas claras. Su caracter&iacute;stica m&aacute;s distintiva es el </span><strong><span dir=\"auto\">intenso color rojo carmes&iacute; de su pecho y garganta</span></strong><span dir=\"auto\"> , que contrasta fuertemente con una banda blanca que lo separa del negro de la cabeza y una franja superciliar (ceja) blanca. Las hembras tienen el rojo del pecho menos intenso y m&aacute;s p&aacute;lido, y su plumaje general es m&aacute;s opaco. Posee un pico c&oacute;nico y puntiagudo, adaptado para alimentarse tanto de insectos como de semillas.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n en Chile:</span></strong><span dir=\"auto\"> En Chile, la loica se distribuye desde la Regi&oacute;n de Atacama hasta el Cabo de Hornos en Magallanes. Habita en una gran variedad de ambientes abiertos y semiabiertos, incluyendo praderas, pastizales, campos agr&iacute;colas, estepas, matorralales y bordes de caminos. Es una especie muy com&uacute;n y visible en zonas rurales y periurbanas.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Es un ave principalmente insect&iacute;vora, aunque su dieta tambi&eacute;n incluye semillas, granos y algunos frutos, especialmente en invierno. Forrajea mayormente en el suelo, caminando entre la vegetaci&oacute;n. Son aves sociables, que a menudo se congregan en grupos, especialmente fuera de la &eacute;poca reproductiva. Su canto es fuerte, claro y melodioso, a menudo emitido desde un posadero prominente, y sirve tanto para la comunicaci&oacute;n territorial como para la atracci&oacute;n de pareja. Construyen nidos en el suelo, bien camuflados entre la vegetaci&oacute;n.</span></p>\r\n</li>\r\n<li>\r\n<p><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\"> La loica est&aacute; clasificada como \"Preocupaci&oacute;n Menor\" a nivel global y nacional en Chile, debido a su amplia distribuci&oacute;n y poblaciones estables. Su capacidad para adaptarse a ambientes modificados por la actividad humana (campos agr&iacute;colas, zonas ganaderas) ha contribuido a su &eacute;xito. Juega un rol ecol&oacute;gico importante como controladora de insectos y dispersora de semillas, y es una especie valorada por su canto y su vistoso plumaje, siendo un elemento caracter&iacute;stico del paisaje chileno.</span></p>\r\n</li>\r\n</ul>', 'especies/25.png', 'activo', '2025-11-03 20:02:55', '2025-11-03 20:02:55', 5, 5),
(26, 'Lama guanicoe', 'lama-guanicoe-2', 'Guanaco', 6, '<p data-path-to-node=\"2\"><strong><span dir=\"auto\">Lama guanicoe: El Guanaco, Rumiante Emblem&aacute;tico de los Andes y la Patagonia Chilena</span></strong></p>\r\n<p data-path-to-node=\"3\"><span dir=\"auto\">La </span><strong><span dir=\"auto\">Lama guanicoe</span></strong><span dir=\"auto\"> , conocida como guanaco, es un cam&eacute;lido sudamericano silvestre, considerado el ancestro de la llama dom&eacute;stica. Es una especie ic&oacute;nica de los paisajes altoandinos y patag&oacute;nicos de Chile, adaptada a condiciones extremas de altitud y clima.</span></p>\r\n<p data-path-to-node=\"4\"><strong><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul data-path-to-node=\"5\">\r\n<li>\r\n<p data-path-to-node=\"5,0,0\"><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Posee un cuerpo esbelto y patas largas, cubierto por un pelaje denso y lanoso de color caf&eacute; claro a rojizo en el dorso, que se aclara a blanquecino en el vientre y las patas internas. Su cabeza es de color gris&aacute;ceo con orejas largas y puntiagudas. Los adultos miden entre 1,5 y 1,9 metros de altura a la cruz y pesan entre 90 y 140 kg.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,1,0\"><strong><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n en Chile:</span></strong><span dir=\"auto\"> El guanaco se distribuye a lo largo de toda la costa de Chile. Se encuentra desde la Regi&oacute;n de Arica y Parinacota por el norte hasta las islas de Tierra del Fuego por el sur. Habita una amplia variedad de ecosistemas, desde estepas altoandinas y desiertos fr&iacute;os hasta pastizales patag&oacute;nicos, prefiriendo zonas con vegetaci&oacute;n abierta que le permiten detectar depredadores.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,2,0\"><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Es un herb&iacute;voro que se alimenta de pastos, arbustos y l&iacute;quenes, adaptando su dieta seg&uacute;n la disponibilidad estacional. Vive en grupos sociales que var&iacute;an desde harenes (un macho, varias hembras y sus cr&iacute;as) hasta grupos de machos solteros. Son animales altamente adaptados a la escasez de agua, obteniendo gran parte de ella de la vegetaci&oacute;n que consumen. Son r&aacute;pidos y &aacute;giles, claves habilidades para evadir a depredadores como el puma.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,3,0\"><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\"> Aunque su poblaci&oacute;n ha disminuido hist&oacute;ricamente debido a la caza y la competencia con el ganado, actualmente goza de protecci&oacute;n y sus poblaciones se han recuperado en algunas &aacute;reas. Es una especie clave en el mantenimiento de la biodiversidad de los ecosistemas donde habita, actuando como dispersor de semillas y contribuyendo a la salud de los pastizales. Su conservaci&oacute;n es fundamental para la integridad ecol&oacute;gica de los Andes y la Patagonia.</span></p>\r\n</li>\r\n</ul>', 'especies/26.png', 'activo', '2025-11-19 09:13:08', '2025-11-19 09:13:08', 5, 5),
(27, 'Hippocamelus bisulcus ', 'hippocamelus-bisulcus', 'Huemul', 6, '<p data-path-to-node=\"2\"><strong class=\"\"><span dir=\"auto\">Hippocamelus bisulcus: El Huemul del Sur, Ciervo End&eacute;mico y Monumento Natural de Chile</span></strong></p>\r\n<p data-path-to-node=\"3\"><span dir=\"auto\">La</span><strong class=\"\"><span dir=\"auto\"> Hippocamelus bisulcus </span></strong><span dir=\"auto\">, </span><span dir=\"auto\">conocido como huemul del sur o ciervo andino, </span><span dir=\"auto\">es un c&eacute;rvido end&eacute;mico de los Andes patag&oacute;nicos de Chile y Argentina, </span><span dir=\"auto\">y un emblema de la fauna silvestre chilena, </span><span dir=\"auto\">declarado Monumento Natural. </span><span dir=\"auto\">Es una especie adaptada a ambientes monta&ntilde;osos y boscosos, </span><span dir=\"auto\">cuya conservaci&oacute;n es de vital importancia.</span></p>\r\n<p data-path-to-node=\"4\"><strong class=\"\"><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul data-path-to-node=\"5\">\r\n<li>\r\n<p data-path-to-node=\"5,0,0\"><strong class=\"\"><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es un c&eacute;rvido de tama&ntilde;o mediano (altura a la cruz de 80-110 cm; peso de 40-100 kg), </span><span dir=\"auto\">con un cuerpo robusto y patas relativamente cortas y fuertes, </span><span dir=\"auto\">adaptadas para moverse en terrenos dif&iacute;ciles. </span><span dir=\"auto\">Su pelaje es denso y &aacute;spero, </span><span dir=\"auto\">de color pardo gris&aacute;ceo oscuro en el dorso, </span><span dir=\"auto\">aclar&aacute;ndose hacia el vientre. </span><span dir=\"auto\">Los machos poseen astas bifurcadas que pueden alcanzar hasta 30 cm de largo y que se renuevan anualmente. </span><span dir=\"auto\">Su cabeza es de color similar al cuerpo, </span><span dir=\"auto\">con manchas negras caracter&iacute;sticas en el hocico y frente, </span><span dir=\"auto\">y orejas grandes y redondeadas.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,1,0\"><strong><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n en Chile:</span></strong><span dir=\"auto\">En Chile,</span><span dir=\"auto\">el huemul del sur se distribuye de manera fragmentada en la Cordillera de los Andes y la Patagonia,</span><span dir=\"auto\">desde la Regi&oacute;n de &Ntilde;uble hasta la Regi&oacute;n de Magallanes.</span><span dir=\"auto\">Habita en una diversidad de ambientes que incluyen bosques templados de lenga y coihue,</span><span dir=\"auto\">matorales densos,</span><span dir=\"auto\">pastizales de altura y zonas rocosas,</span><span dir=\"auto\">siempre asociados a cuerpos de agua dulce.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,2,0\"><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\">Es un herb&iacute;voro que se alimenta de brotes,</span><span dir=\"auto\">hojas,</span><span dir=\"auto\">s,</span><span dir=\"auto\">l&iacute;quenes y pastos.</span><span dir=\"auto\">Son animales diurnos y relativamente solitarios o viven en peque&ntilde;os grupos familiares.</span><span dir=\"auto\">Su comportamiento de \"pisoteo\" en zonas de alimentaci&oacute;n ayuda a mantener la diversidad de la vegetaci&oacute;n.</span><span dir=\"auto\">Son excelentes escaladores en terrenos abruptos y su principal defensa contra depredadores como el puma es el camuflaje y la huida r&aacute;pida.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,3,0\"><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\">El huemul del sur est&aacute; clasificado como \"En Peligro\" a nivel global y nacional.</span><span dir=\"auto\">Sus principales amenazas son la p&eacute;rdida y fragmentaci&oacute;n de su h&aacute;bitat debido a la tala de bosques,</span><span dir=\"auto\">el avance de la ganader&iacute;a y la construcci&oacute;n de infraestructura,</span><span dir=\"auto\">la competencia con el ganado dom&eacute;stico,</span><span dir=\"auto\">la depredaci&oacute;n por perros asilvestrados y la caza furtiva.</span><span dir=\"auto\">Su protecci&oacute;n es fundamental no solo por ser un s&iacute;mbolo nacional,</span><span dir=\"auto\">sino tambi&eacute;n por su rol ecol&oacute;gico en los bosques templados y андinos,</span><span dir=\"auto\">siendo un bioindicador de la salud de estos ecosistemas fr&aacute;giles.</span></p>\r\n</li>\r\n</ul>', 'especies/27.png', 'inactivo', '2025-11-19 09:16:11', '2025-11-19 09:19:26', 5, 5);
INSERT INTO `especies` (`esp_id`, `esp_nombre_cientifico`, `esp_slug`, `esp_nombre_comun`, `esp_tax_id`, `esp_descripcion`, `esp_imagen`, `esp_estado`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(28, 'Hippocamelus bisulcus', 'hippocamelus-bisulcus-2', 'Huemul', 6, '<p data-path-to-node=\"2\"><strong class=\"\"><span dir=\"auto\">Hippocamelus bisulcus: El Huemul del Sur, Ciervo End&eacute;mico y Monumento Natural de Chile</span></strong></p>\r\n<p data-path-to-node=\"3\"><span dir=\"auto\">La</span><strong class=\"\"><span dir=\"auto\"> Hippocamelus bisulcus </span></strong><span dir=\"auto\">, </span><span dir=\"auto\">conocido como huemul del sur o ciervo andino, </span><span dir=\"auto\">es un c&eacute;rvido end&eacute;mico de los Andes patag&oacute;nicos de Chile y Argentina, </span><span dir=\"auto\">y un emblema de la fauna silvestre chilena, </span><span dir=\"auto\">declarado Monumento Natural. </span><span dir=\"auto\">Es una especie adaptada a ambientes monta&ntilde;osos y boscosos, </span><span dir=\"auto\">cuya conservaci&oacute;n es de vital importancia.</span></p>\r\n<p data-path-to-node=\"4\"><strong class=\"\"><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul data-path-to-node=\"5\">\r\n<li>\r\n<p data-path-to-node=\"5,0,0\"><strong class=\"\"><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es un c&eacute;rvido de tama&ntilde;o mediano (altura a la cruz de 80-110 cm; peso de 40-100 kg), </span><span dir=\"auto\">con un cuerpo robusto y patas relativamente cortas y fuertes, </span><span dir=\"auto\">adaptadas para moverse en terrenos dif&iacute;ciles. </span><span dir=\"auto\">Su pelaje es denso y &aacute;spero, </span><span dir=\"auto\">de color pardo gris&aacute;ceo oscuro en el dorso, </span><span dir=\"auto\">aclar&aacute;ndose hacia el vientre. </span><span dir=\"auto\">Los machos poseen astas bifurcadas que pueden alcanzar hasta 30 cm de largo y que se renuevan anualmente. </span><span dir=\"auto\">Su cabeza es de color similar al cuerpo, </span><span dir=\"auto\">con manchas negras caracter&iacute;sticas en el hocico y frente, </span><span dir=\"auto\">y orejas grandes y redondeadas.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,1,0\"><strong><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n en Chile:</span></strong><span dir=\"auto\">En Chile,</span><span dir=\"auto\">el huemul del sur se distribuye de manera fragmentada en la Cordillera de los Andes y la Patagonia,</span><span dir=\"auto\">desde la Regi&oacute;n de &Ntilde;uble hasta la Regi&oacute;n de Magallanes.</span><span dir=\"auto\">Habita en una diversidad de ambientes que incluyen bosques templados de lenga y coihue,</span><span dir=\"auto\">matorales densos,</span><span dir=\"auto\">pastizales de altura y zonas rocosas,</span><span dir=\"auto\">siempre asociados a cuerpos de agua dulce.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,2,0\"><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\">Es un herb&iacute;voro que se alimenta de brotes,</span><span dir=\"auto\">hojas,</span><span dir=\"auto\">s,</span><span dir=\"auto\">l&iacute;quenes y pastos.</span><span dir=\"auto\">Son animales diurnos y relativamente solitarios o viven en peque&ntilde;os grupos familiares.</span><span dir=\"auto\">Su comportamiento de \"pisoteo\" en zonas de alimentaci&oacute;n ayuda a mantener la diversidad de la vegetaci&oacute;n.</span><span dir=\"auto\">Son excelentes escaladores en terrenos abruptos y su principal defensa contra depredadores como el puma es el camuflaje y la huida r&aacute;pida.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,3,0\"><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\">El huemul del sur est&aacute; clasificado como \"En Peligro\" a nivel global y nacional.</span><span dir=\"auto\">Sus principales amenazas son la p&eacute;rdida y fragmentaci&oacute;n de su h&aacute;bitat debido a la tala de bosques,</span><span dir=\"auto\">el avance de la ganader&iacute;a y la construcci&oacute;n de infraestructura,</span><span dir=\"auto\">la competencia con el ganado dom&eacute;stico,</span><span dir=\"auto\">la depredaci&oacute;n por perros asilvestrados y la caza furtiva.</span><span dir=\"auto\">Su protecci&oacute;n es fundamental no solo por ser un s&iacute;mbolo nacional,</span><span dir=\"auto\">sino tambi&eacute;n por su rol ecol&oacute;gico en los bosques templados y андinos,</span><span dir=\"auto\">siendo un bioindicador de la salud de estos ecosistemas fr&aacute;giles.</span></p>\r\n</li>\r\n</ul>', 'especies/28.png', 'activo', '2025-11-19 09:18:55', '2025-11-19 09:18:55', 5, 5),
(29, 'Dromiciops bozinovici ', 'dromiciops-bozinovici', 'Monito del monte', 6, '<p data-path-to-node=\"3\"><span dir=\"auto\">La</span><strong class=\"\"><span dir=\"auto\"> Dromiciops bozinovici </span></strong><span dir=\"auto\">, </span><span dir=\"auto\">conocida como monito del monte de Bozinovic, </span><span dir=\"auto\">es una de las tres especies reconocidas de monito del monte, </span><span dir=\"auto\">un peque&ntilde;o marsupial end&eacute;mico de los bosques templados de Sudam&eacute;rica. </span><span dir=\"auto\">Es un f&oacute;sil viviente y el &uacute;nico representante actual del orden Microbiotheria, </span><span dir=\"auto\">lo que lo convierte en un mam&iacute;fero de extraordinario inter&eacute;s evolutivo y ecol&oacute;gico, </span><span dir=\"auto\">crucial para la salud de los bosques nativos de Chile.</span></p>\r\n<p data-path-to-node=\"4\"><strong class=\"\"><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul data-path-to-node=\"5\">\r\n<li>\r\n<p data-path-to-node=\"5,0,0\"><strong class=\"\"><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es un marsupial peque&ntilde;o (longitud cabeza-cuerpo de 8-13 cm; peso de 16-42 g), </span><span dir=\"auto\">con un cuerpo compacto y una cola prensil casi tan larga como su cuerpo. </span><span dir=\"auto\">Su pelaje es denso, </span><span dir=\"auto\">suave y de color pardo gris&aacute;ceo en el dorso, </span><span dir=\"auto\">con el vientre m&aacute;s claro y un caracter&iacute;stico \"antifaz\" negro alrededor de sus grandes ojos oscuros. </span><span dir=\"auto\">Sus orejas son peque&ntilde;as y redondeadas. </span><span dir=\"auto\">Las hembras poseen una bolsa marsupial bien desarrollada para la cr&iacute;a.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,1,0\"><strong class=\"\"><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n en Chile:</span></strong><span dir=\"auto\"><em class=\"\"> Dromiciops bozinovici </em>fue descrito m&aacute;s recientemente que <em class=\"\">Dromiciops gliroides </em>y su distribuci&oacute;n se concentra en zonas espec&iacute;ficas de los bosques templados de Chile, principalmente entre las regiones del Maule y La Araucan&iacute;a. Habita exclusivamente en los densos bosques templados lluviosos (selva valdiviana), especialmente aquellos con abundancia de copihues (Lapageria rosea), quila y colihue, que le proporcionan refugio y alimento.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,2,0\"><strong class=\"\"><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Es un animal nocturno y arbor&iacute;cola, </span><span dir=\"auto\">que se mueve con destreza entre las ramas. </span><span dir=\"auto\">Su dieta es omnivora, </span><span dir=\"auto\">consistiendo principalmente en frutos y semillas, </span><span dir=\"auto\">pero tambi&eacute;n insectos y larvas. </span><span dir=\"auto\">Juega un rol ecol&oacute;gico fundamental como </span><strong class=\"\"><span dir=\"auto\">dispersor de semillas </span></strong><span dir=\"auto\">de muchas especies de plantas nativas, </span><span dir=\"auto\">incluido el copihue, </span><span dir=\"auto\">contribuyendo a la regeneraci&oacute;n del bosque. </span><span dir=\"auto\">Durante el invierno o en periodos de escasez de alimento, </span><span dir=\"auto\">puede entrar en un estado de letargo o hibernaci&oacute;n, </span><span dir=\"auto\">disminuyendo su actividad metab&oacute;lica. </span><span dir=\"auto\">Construye nidos esf&eacute;ricos con musgos y hojas, </span><span dir=\"auto\">a menudo en huecos de &aacute;rboles o entre la vegetaci&oacute;n densa.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,3,0\"><strong class=\"\"><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\"> Las tres especies de </span><em class=\"\"><span dir=\"auto\">Dromiciops </span></em><span dir=\"auto\">est&aacute;n clasificadas como \"Casi Amenazado\" a \"Vulnerable\". </span><span dir=\"auto\"><em class=\"\">D. bozinovici </em>enfrenta las mismas amenazas que sus parientes: la principal es la p&eacute;rdida y fragmentaci&oacute;n de su h&aacute;bitat debido a la deforestaci&oacute;n (especialmente para plantaciones ex&oacute;ticas), los incendios y la urbanizaci&oacute;n. Tambi&eacute;n sufre depredaci&oacute;n por especies introducidas como gatos y ratas. Su singularidad evolutiva como el &uacute;ltimo miembro viviente de un antiguo linaje de marsupiales y su papel vital en la din&aacute;mica de los bosques templados lo convierte en una especie prioritaria para la conservaci&oacute;n de la biodiversidad chilena.</span></p>\r\n</li>\r\n</ul>', 'especies/29.png', 'activo', '2025-11-19 09:20:24', '2025-11-19 09:20:38', 5, 5),
(30, 'Equus ferus caballus', 'equus-ferus-caballus', 'Caballo', 6, '<p data-path-to-node=\"2\"><strong><span dir=\"auto\">Equus ferus caballus: El Caballo Dom&eacute;stico, Ungulado de Profunda Influencia en la Civilizaci&oacute;n Humana y los Ecosistemas</span></strong></p>\r\n<p data-path-to-node=\"3\"><span dir=\"auto\">La </span><strong><span dir=\"auto\">Equus ferus caballus</span></strong><span dir=\"auto\"> , com&uacute;n conocido como caballo dom&eacute;stico, es una subespecie domesticada del caballo salvaje ( </span><em><span dir=\"auto\">Equus ferus</span></em><span dir=\"auto\"> ). Con una historia de domesticaci&oacute;n que abarca miles de a&ntilde;os, ha desempe&ntilde;ado un papel fundamental en el desarrollo de las civilizaciones humanas a nivel global, tanto en el trabajo, el transporte, la guerra y el deporte. Su presencia tambi&eacute;n implica consideraciones ecol&oacute;gicas en los ambientes donde interact&uacute;a.</span></p>\r\n<p data-path-to-node=\"4\"><strong><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul data-path-to-node=\"5\">\r\n<li>\r\n<p data-path-to-node=\"5,0,0\"><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> La morfolog&iacute;a del caballo es extremadamente variada debido a la cr&iacute;a selectiva para diferentes prop&oacute;sitos. Var&iacute;an significativamente en tama&ntilde;o, desde ponis peque&ntilde;os hasta razas de tiro grandes, con alturas a la cruz que van desde menos de 1 metro hasta m&aacute;s de 1,8 metros, y pesos que oscilan entre 200 y m&aacute;s de 1000 kg. Su pelaje puede ser de diversos colores (bayos, negros, casta&ntilde;os, blancos, alazanes, etc.) y patrones. Poseen crines y colas largas, una dentadura adaptada al pastoreo y un solo dedo con pezu&ntilde;a en cada pata.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,1,0\"><strong><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n:</span></strong><span dir=\"auto\"> Globalmente distribuido, el caballo dom&eacute;stico se encuentra en todos los continentes, excepto la Ant&aacute;rtida, dondequiera que los humanos los hayan llevado. Se adapta a una amplia gama de climas y terrenos. En Chile, los caballos son omnipresentes, utilizados en la agricultura, la ganader&iacute;a (especialmente en el sur y la Patagonia), el deporte, el turismo y como medio de transporte en &aacute;reas rurales y monta&ntilde;osas. Tambi&eacute;n existen poblaciones de caballos asilvestrados en algunas regiones.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,2,0\"><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Son herb&iacute;voros que se alimentan principalmente de pastos y forraje, aunque pueden ramonear arbustos y comer granos cuando est&aacute;n disponibles. Son animales gregarios, viviendo en manadas con estructuras sociales jer&aacute;rquicas. En su estado natural o asilvestrado, se organizan en grupos con un l&iacute;der semental y varias yeguas con sus cr&iacute;as. Son conocidos por su velocidad, resistencia y comportamiento de huida ante el peligro.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,3,0\"><strong><span dir=\"auto\">Impacto y Relevancia (Chile):</span></strong><span dir=\"auto\"> El caballo tiene un inmenso valor cultural, hist&oacute;rico y econ&oacute;mico en Chile, siendo un compa&ntilde;ero indispensable en el campo y parte de la identidad huasa. Sin embargo, su manejo, especialmente en grandes densidades o en r&eacute;gimen asilvestrado, puede generar impactos ecol&oacute;gicos:</span></p>\r\n<ul data-path-to-node=\"5,3,1\">\r\n<li>\r\n<p data-path-to-node=\"5,3,1,0,0\"><strong><span dir=\"auto\">Sobrepastoreo:</span></strong><span dir=\"auto\"> El consumo de vegetaci&oacute;n por grandes poblaciones de caballos puede llevar a la degradaci&oacute;n de pastizales nativos, la alteraci&oacute;n de la composici&oacute;n flor&iacute;stica y la erosi&oacute;n del suelo, particularmente en &aacute;reas sensibles o de alta fragilidad.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,3,1,1,0\"><strong><span dir=\"auto\">Competencia:</span></strong><span dir=\"auto\"> Compiten por recursos alimenticios y de agua con herb&iacute;voros nativos como guanacos y huemules, lo que puede afectar sus poblaciones.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,3,1,2,0\"><strong><span dir=\"auto\">Alteraci&oacute;n del suelo:</span></strong><span dir=\"auto\"> El pisoteo de los cascos puede compactar el suelo y afectar la infiltraci&oacute;n de agua. La gesti&oacute;n responsable de los caballos, incluyendo el control de poblaciones asilvestradas y pr&aacute;cticas de pastoreo sostenibles, es fundamental para minimizar su impacto en los ecosistemas naturales y asegurar la coexistencia con la fauna nativa.</span></p>\r\n</li>\r\n</ul>\r\n</li>\r\n</ul>', 'especies/30.png', 'activo', '2025-11-19 09:21:55', '2025-11-19 09:21:55', 5, 5),
(31, 'Capra hircus ', 'capra-hircus', 'Cabra', 6, '<p data-path-to-node=\"2\"><strong><span dir=\"auto\">Capra hircus: La Cabra Dom&eacute;stica, Ungulado Adaptable con Impacto Socioecon&oacute;mico y Ecol&oacute;gico</span></strong></p>\r\n<p data-path-to-node=\"3\"><span dir=\"auto\">La </span><strong><span dir=\"auto\">Capra hircus</span></strong><span dir=\"auto\"> , conocida como cabra dom&eacute;stica, es un mam&iacute;fero rumiante domesticado a partir de la cabra salvaje ( </span><em><span dir=\"auto\">Capra aegagrus</span></em><span dir=\"auto\"> ) hace millas de a&ntilde;os. Es una de las primeras especies animales en ser domesticadas y se ha adaptado a una amplia variedad de climas y terrenos, siendo un pilar econ&oacute;mico en muchas regiones del mundo, incluyendo Chile, pero tambi&eacute;n un agente de transformaci&oacute;n ambiental.</span></p>\r\n<p data-path-to-node=\"4\"><strong><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul data-path-to-node=\"5\">\r\n<li>\r\n<p data-path-to-node=\"5,0,0\"><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Extremadamente diversa debido a la cr&iacute;a selectiva. Generalmente, tienen un cuerpo compacto, con pelaje que var&iacute;a en color, longitud y textura (desde corto hasta largo y lanoso). Ambos sexos pueden tener cuernos (a menudo curvados hacia atr&aacute;s o en espiral) y una barba o \"perilla\". Las hembras (cabras) y los machos (machos cabr&iacute;os o chivos) tienen pezu&ntilde;as hendidas.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,1,0\"><strong><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n:</span></strong><span dir=\"auto\"> Globalmente distribuida, la cabra dom&eacute;stica se cr&iacute;a en casi todos los ecosistemas terrestres, desde desiertos y zonas &aacute;ridas hasta monta&ntilde;as y matorrales. En Chile, la ganader&iacute;a caprina es tradicional en zonas semi&aacute;ridas del norte y centro (regiones de Coquimbo, Valpara&iacute;so) y en zonas monta&ntilde;osas, adapt&aacute;ndose a condiciones de escasez de agua y vegetaci&oacute;n.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,2,0\"><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Son herb&iacute;voros ramoneadores, lo que significa que prefieren arbustos, hojas y brotes sobre los pastos, aunque tambi&eacute;n consumen una amplia variedad de vegetaci&oacute;n. Son animales gregarios, viviendo en reba&ntilde;os. Su comportamiento de b&uacute;squeda de alimentos es muy exploratorio y son capaces de escalar terrenos rocosos y escarpados. Su alta resistencia y adaptabilidad a condiciones dif&iacute;ciles las hacen valiosas para la subsistencia en ambientes marginales.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,3,0\"><strong><span dir=\"auto\">Impacto y Relevancia (Chile):</span></strong><span dir=\"auto\"> En Chile, la cabra dom&eacute;stica es fundamental para la subsistencia de las comunidades rurales, proporcionando carne, leche, queso y lana. Sin embargo, su presencia y manejo, especialmente en altas densidades o en r&eacute;gimen de libre pastoreo, pueden tener importantes impactos ecol&oacute;gicos en ambientes fr&aacute;giles:</span></p>\r\n<ul data-path-to-node=\"5,3,1\">\r\n<li>\r\n<p data-path-to-node=\"5,3,1,0,0\"><strong><span dir=\"auto\">Sobrepastoreo y Erosi&oacute;n:</span></strong><span dir=\"auto\"> Al ser ramoneadoras, pueden consumir la vegetaci&oacute;n arbustiva y arb&oacute;rea joven, impidiendo la regeneraci&oacute;n de bosques y matorrales. Su pastoreo intensivo y el pisoteo contribuyen a la degradaci&oacute;n del suelo y la desertificaci&oacute;n, especialmente en zonas semi&aacute;ridas.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,3,1,1,0\"><strong><span dir=\"auto\">Competencia:</span></strong><span dir=\"auto\"> Compiten con herb&iacute;voros nativos por recursos, y su capacidad de acceder a terrenos dif&iacute;ciles las hace eficientes en la explotaci&oacute;n de recursos.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,3,1,2,0\"><strong><span dir=\"auto\">Alteraci&oacute;n de H&aacute;bitat:</span></strong><span dir=\"auto\"> Su presencia puede modificar la estructura de la vegetaci&oacute;n y los procesos ecol&oacute;gicos. La gesti&oacute;n sostenible de la ganader&iacute;a caprina, que integra las necesidades de las comunidades con la conservaci&oacute;n de los ecosistemas, es un desaf&iacute;o clave en las regiones donde se practica esta actividad.</span></p>\r\n</li>\r\n</ul>\r\n</li>\r\n</ul>', 'especies/31.png', 'activo', '2025-11-19 09:23:17', '2025-11-19 09:23:17', 5, 5),
(32, 'Conepatus chinga ', 'conepatus-chinga', 'Chingue', 6, '<ul data-path-to-node=\"6\">\r\n<li>\r\n<p data-path-to-node=\"3\"><strong><span dir=\"auto\">Conepatus chinga: El Chingue, Must&eacute;lido Terrestre de Sudam&eacute;rica y Caracter&iacute;stico Emisor de Olores</span></strong></p>\r\n<p data-path-to-node=\"4\"><span dir=\"auto\">La </span><strong><span dir=\"auto\">Conepatus chinga</span></strong><span dir=\"auto\"> , com&uacute;nmente conocido como chingue o zorrino, es un must&eacute;lido terrestre nativo de Sudam&eacute;rica. Es una especie ampliamente reconocida por su distintiva coloraci&oacute;n blanco y negro y su capacidad de emitir un l&iacute;quido maloliente como mecanismo de defensa, siendo un componente notable de la fauna de diversos ecosistemas chilenos.</span></p>\r\n<p data-path-to-node=\"5\"><strong><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul data-path-to-node=\"6\">\r\n<li>\r\n<p data-path-to-node=\"6,0,0\"><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es un must&eacute;lido de tama&ntilde;o mediano (longitud cabeza-cuerpo de 30-49 cm; peso de 1,5-4,5 kg). Su cuerpo es robusto y compacto, cubierto por un pelaje denso blanco y generalmente negro brillante, con una o dos franjas gruesas que nacen en la cabeza y recorren el dorso y la cola. La cola es larga y tupida, a menudo con una porci&oacute;n blanca prominente. Posee una cabeza peque&ntilde;a, hocico puntiagudo y patas con garras fuertes, aptas para excavar.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"6,1,0\"><strong><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n en Chile:</span></strong><span dir=\"auto\"> En Chile, el chingue se distribuye desde la Regi&oacute;n de Arica y Parinacota hasta la Regi&oacute;n de Magallanes. Es una especie adaptable que habita en una amplia variedad de ecosistemas, incluyendo estepas, matorrales, pastizales, zonas agr&iacute;colas, bordes de bosques y &aacute;reas semi&aacute;ridas, desde el nivel del mar hasta altitudes elevadas en los Andes.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"6,2,0\"><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Es un animal omn&iacute;voro y muy oportunista. Su dieta es muy variada e incluye insectos (su alimento principal), larvas, peque&ntilde;os roedores, huevos de aves, reptiles, carro&ntilde;a y una proporci&oacute;n de materia vegetal como frutos. Es un animal principalmente nocturno o crepuscular, y solitario. Utiliza cuevas, madrigueras abandonadas o excavaciones propias para refugiarse y criar a sus cr&iacute;as. Su mecanismo de defensa m&aacute;s conocido es la emisi&oacute;n de un l&iacute;quido maloliente y persistente desde sus gl&aacute;ndulas anales, el cual puede rociar con gran precisi&oacute;n a una distancia considerable si se siente amenazado.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"6,3,0\"><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\"> El chingue est&aacute; clasificado como \"Preocupaci&oacute;n Menor\" a nivel global y nacional en Chile, debido a su amplia distribuci&oacute;n y poblaciones estables. Aunque a veces es perseguido por los humanos debido al mal olor que emite o por su potencial depredaci&oacute;n sobre aves de corral, su rol ecol&oacute;gico como controlador de poblaciones de insectos y roedores es importante para el equilibrio de los ecosistemas donde habita.</span></p>\r\n</li>\r\n</ul>\r\n</li>\r\n</ul>', 'especies/32.png', 'activo', '2025-11-19 09:25:08', '2025-11-19 09:25:08', 5, 5),
(33, 'Myocastor coypus ', 'myocastor-coypus-2', 'Coipo', 6, '<p data-path-to-node=\"2\"><strong><span dir=\"auto\">Myocastor coypus: El Coipo, Roedor Semiacu&aacute;tico de los Humedales Chilenos</span></strong></p>\r\n<p data-path-to-node=\"3\"><span dir=\"auto\">La </span><strong><span dir=\"auto\">Myocastor coypus</span></strong><span dir=\"auto\"> , conocida como coipo o nutria roedora, es un roedor semiacu&aacute;tico de gran tama&ntilde;o, nativo de Sudam&eacute;rica, con una presencia importante en los humedales y cuerpos de agua dulce de Chile. Es un animal herb&iacute;voro bien adaptado a la vida en el agua y un componente distintivo de la fauna de las cuencas hidrogr&aacute;ficas.</span></p>\r\n<p data-path-to-node=\"4\"><strong><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul data-path-to-node=\"5\">\r\n<li>\r\n<p data-path-to-node=\"5,0,0\"><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es el roedor m&aacute;s grande de Chile, con una longitud cabeza-cuerpo que puede alcanzar los 60 cm y un peso de 5 a 9 kg. Su cuerpo es robusto, cubierto por un pelaje denso y pardo, con una capa interna de subpelo gris oscuro muy fino y valorado hist&oacute;ricamente por su piel. Posee una cola larga, cil&iacute;ndrica y escamosa, y patas traseras palmeadas, adaptaciones perfectas para la nataci&oacute;n. Sus incisivos son grandes y de un caracter&iacute;stico color anaranjado brillante.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,1,0\"><strong><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n en Chile:</span></strong><span dir=\"auto\"> En Chile, el coipo se distribuye principalmente desde la Regi&oacute;n de Coquimbo hasta la Regi&oacute;n de Los Lagos, aunque su distribuci&oacute;n puede ser discontinua y ha habido avistamientos m&aacute;s al sur. Habita en una variedad de ambientes acu&aacute;ticos de agua dulce o salobre, incluyendo r&iacute;os, lagos, lagunas, pantanos, esteros y zonas de marisma, donde la vegetaci&oacute;n ribere&ntilde;a es abundante.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,2,0\"><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Es un animal principalmente herb&iacute;voro, aliment&aacute;ndose de tallos, hojas, ra&iacute;ces y rizomas de plantas acu&aacute;ticas y terrestres. Construye madrigueras complejas en las riberas o utiliza plataformas de vegetaci&oacute;n flotante. Es predominantemente crepuscular y nocturno, aunque puede observarse durante el d&iacute;a, especialmente en zonas con baja perturbaci&oacute;n humana. Son excelentes nadadores y buceadores, capaces de permanecer sumergidos por varios minutos.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,3,0\"><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\"> A nivel global, el coipo es considerado una especie invasora en varias partes del mundo debido a fugas de granjas peleteras, causando impactos ecol&oacute;gicos y econ&oacute;micos. Sin embargo, en su rango nativo en Chile, juega un papel ecol&oacute;gico en la estructuraci&oacute;n de la vegetaci&oacute;n de humedales. Su estado de conservaci&oacute;n en Chile no es cr&iacute;tico, pero su presencia es un buen indicador de la salud de los humedales.</span></p>\r\n</li>\r\n</ul>', 'especies/33.png', 'activo', '2025-11-19 09:26:14', '2025-11-19 09:26:14', 5, 5),
(34, 'Tyto alba ', 'tyto-alba', 'Lechuza', 4, '<p data-path-to-node=\"2\"><strong><span dir=\"auto\">Tyto alba: La Lechuza Com&uacute;n (Lechuza Blanca), Cazadora Nocturna y Controladora de Roedores Global</span></strong></p>\r\n<p data-path-to-node=\"3\"><span dir=\"auto\">La </span><strong><span dir=\"auto\">Tyto alba</span></strong><span dir=\"auto\"> , conocida como lechuza com&uacute;n o lechuza blanca, es una de las aves rapaces nocturnas m&aacute;s ampliamente distribuidas en el mundo, presente en todos los continentes excepto la Ant&aacute;rtida. Es un depredador silencioso y eficiente, que juega un papel crucial en el control de poblaciones de roedores, tanto en ecosistemas naturales como en ambientes agr&iacute;colas y urbanos de Chile.</span></p>\r\n<p data-path-to-node=\"4\"><strong><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul data-path-to-node=\"5\">\r\n<li>\r\n<p data-path-to-node=\"5,0,0\"><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es una lechuza de tama&ntilde;o mediano (longitud de 33-39 cm; envergadura de 80-95 cm). Su plumaje es inconfundible: blanco puro en el vientre y la parte inferior de las alas, con la espalda y la cabeza de color dorado-marr&oacute;n p&aacute;lido, finalmente moteado de gris y negro. La caracter&iacute;stica m&aacute;s distintiva es su cara en forma de coraz&oacute;n (disco facial), de color blanco, que act&uacute;a como una \"par&aacute;bola\" para dirigir el sonido hacia sus o&iacute;dos. Sus ojos son peque&ntilde;os y oscuros.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,1,0\"><strong><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n en Chile:</span></strong><span dir=\"auto\"> En Chile, la lechuza com&uacute;n se distribuye desde la Regi&oacute;n de Arica y Parinacota hasta la Regi&oacute;n de Magallanes. Es una especie altamente adaptable que habita en una amplia variedad de ecosistemas abiertos y semiabiertos, incluyendo campos agr&iacute;colas, praderas, matorrales, zonas des&eacute;rticas y &aacute;reas urbanas. Requiere de sitios de nidificaci&oacute;n y descanso protegidos, como graneros, campanarios, construcciones abandonadas, huecos de &aacute;rboles o acantilados.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,2,0\"><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Es una rapaz estrictamente nocturna, con una audici&oacute;n extremadamente aguda y una visi&oacute;n adaptada a la baja luminosidad, que le permiten cazar en completa oscuridad. Su dieta se compone casi exclusivamente de roedores peque&ntilde;os y medianos (ratones, ratas, tucos), aunque ocasionalmente consume aves, insectos grandes o anfibios. Posee un vuelo silencioso gracias a la estructura de sus plumas. Nidifica en cavidades y pone entre 4 y 7 huevos.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,3,0\"><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\"> La lechuza com&uacute;n est&aacute; clasificada como \"Preocupaci&oacute;n Menor\" a nivel global y en Chile, debido a su amplia distribuci&oacute;n y poblaciones generalmente estables. Sin embargo, puede ser vulnerable a la p&eacute;rdida de sitios de nidificaci&oacute;n, el uso de pesticidas (que afecta a sus presas y pueden causarle envenenamiento secundario) y las colisiones con veh&iacute;culos o estructuras. Juega un rol ecol&oacute;gico y econ&oacute;mico fundamental como controlador natural de plagas de roedores, beneficiando a la agricultura y reduciendo la necesidad de productos qu&iacute;micos.</span></p>\r\n</li>\r\n</ul>', 'especies/34.png', 'activo', '2025-11-19 09:27:20', '2025-11-19 09:27:20', 5, 5),
(35, 'Ochetorrhynchus melanurus ', 'ochetorrhynchus-melanurus', 'Chiricoca ', 4, '<p data-path-to-node=\"3\"><span dir=\"auto\">La</span><strong class=\"\"><span dir=\"auto\"> Ochetorhynchus melanurus </span></strong><span dir=\"auto\">, </span><span dir=\"auto\">com&uacute;n conocida como colaespina de cordillera o colilarga, </span><span dir=\"auto\">es un ave paseriforme end&eacute;mica de la Cordillera de los Andes de Chile, </span><span dir=\"auto\">perteneciente a la familia Furnariidae. </span><span dir=\"auto\">Es una especie especializada en ambientes rocosos de altura, </span><span dir=\"auto\">donde su presencia es un indicador de la salud de estos fr&aacute;giles ecosistemas monta&ntilde;osos.</span></p>\r\n<p data-path-to-node=\"4\"><strong class=\"\"><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul data-path-to-node=\"5\">\r\n<li>\r\n<p data-path-to-node=\"5,0,0\"><strong class=\"\"><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es un ave peque&ntilde;a (longitud de 14-16 cm), </span><span dir=\"auto\">con un cuerpo compacto y una cola relativamente larga y puntiaguda, </span><span dir=\"auto\">que a menudo mantiene erguida. </span><span dir=\"auto\">Su plumaje es predominantemente pardo-gris&aacute;ceo en el dorso y la cabeza, </span><span dir=\"auto\">con un tono m&aacute;s claro en el vientre y la garganta. </span><span dir=\"auto\">Posee una notaria ceja (superciliar) clara que contrasta con una l&iacute;nea oscura a trav&eacute;s del ojo. </span><span dir=\"auto\">Su pico es fino y ligeramente curvado. </span><span dir=\"auto\">A menudo se le confunde con otras especies de colaespinas, </span><span dir=\"auto\">pero se distingue por su coloraci&oacute;n y h&aacute;bitat espec&iacute;fico.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,1,0\"><strong class=\"\"><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n en Chile:</span></strong><span dir=\"auto\"> La colaespina de cordillera es end&eacute;mica de Chile y se distribuye en la Cordillera de los Andes desde la Regi&oacute;n de Coquimbo hasta la Regi&oacute;n de La Araucan&iacute;a. </span><span dir=\"auto\">Habita exclusivamente en ambientes rocosos de altura, </span><span dir=\"auto\">como laderas rocosas, </span><span dir=\"auto\">quebradas, </span><span dir=\"auto\">acantilados y campos de bloques, </span><span dir=\"auto\">generalmente por encima de los 1. </span><span dir=\"auto\">500 metros sobre el nivel del mar. </span><span dir=\"auto\">Requiere de la presencia de vegetaci&oacute;n arbustiva baja y pastizales cercanos para alimentarse.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,2,0\"><strong class=\"\"><span dir=\"auto\">Ecolog&iacute;a y Comportamiento: </span></strong><span dir=\"auto\">Es un ave insect&iacute;vora, </span><span dir=\"auto\">aliment&aacute;ndose principalmente de insectos y otros invertebrados que busca activamente entre las rocas, </span><span dir=\"auto\">grietas y la vegetaci&oacute;n baja. </span><span dir=\"auto\">Es un ave activa y curiosa, </span><span dir=\"auto\">que se mueve &aacute;gilmente por el terreno rocoso. </span><span dir=\"auto\">Su canto es una serie de notas agudas y repetitivas. </span><span dir=\"auto\">Nidifica en cavidades y grietas de las rocas, </span><span dir=\"auto\">lo que le proporciona protecci&oacute;n contra depredadores y las inclemencias del clima de altura.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,3,0\"><strong class=\"\"><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia: </span></strong><span dir=\"auto\">A nivel nacional en Chile, </span><span dir=\"auto\">la colaespina de cordillera est&aacute; clasificada como \"Preocupaci&oacute;n Menor\". </span><span dir=\"auto\">Sin embargo, </span><span dir=\"auto\">su estatus de endemismo y su dependencia de h&aacute;bitats espec&iacute;ficos de alta monta&ntilde;a lo hacen sensible a los impactos del cambio clim&aacute;tico (que afecta la distribuci&oacute;n de la vegetaci&oacute;n y la disponibilidad de insectos) y las variaciones de su h&aacute;bitat por actividades humanas como la miner&iacute;a o el turismo no regulado. </span><span dir=\"auto\">Su estudio contribuye al conocimiento de la biodiversidad de los Andes chilenos y a la implementaci&oacute;n de estrategias de conservaci&oacute;n para este ecosistema &uacute;nico.</span></p>\r\n</li>\r\n</ul>', 'especies/35.png', 'activo', '2025-11-19 09:29:06', '2025-11-19 09:29:25', 5, 5),
(36, 'Nothoprocta perdicaria ', 'nothoprocta-perdicaria', 'Perdiz Chilena', 4, '<p data-path-to-node=\"3\"><strong><span dir=\"auto\">Nothoprocta perdicaria: La Perdiz Chilena, Ave Terrestre End&eacute;mica de los Campos y Praderas</span></strong></p>\r\n<p data-path-to-node=\"4\"><span dir=\"auto\">La </span><strong><span dir=\"auto\">Nothoprocta perdicaria</span></strong><span dir=\"auto\"> , conocida como perdiz chilena, es un ave terrestre end&eacute;mica de Chile, perteneciente a la familia Tinamidae. Es una especie caracter&iacute;stica de los campos, praderas y zonas agr&iacute;colas de la zona central, valorada por su canto distintivo y su rol en los ecosistemas de pastizales.</span></p>\r\n<p data-path-to-node=\"5\"><strong><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul data-path-to-node=\"6\">\r\n<li>\r\n<p data-path-to-node=\"6,0,0\"><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es un ave de tama&ntilde;o mediano (longitud de 29-32 cm), con un cuerpo compacto y patas cortas, adaptadas para caminar por el suelo. Su plumaje es cr&iacute;ptico, de tonos pardos, gris&aacute;ceos y rojizos, con finas barras y manchas oscuras, lo que le proporciona un excelente camuflaje en la vegetaci&oacute;n. El vientre es m&aacute;s claro, con barras oscuras en los flancos. Posee un pico corto y fino, y ojos peque&ntilde;os. Las alas son cortas y redondeadas, adaptadas para vuelos cortos y explosivos.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"6,1,0\"><strong><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n en Chile:</span></strong><span dir=\"auto\"> La p&eacute;rdida chilena es end&eacute;mica de Chile y se distribuye desde la Regi&oacute;n de Atacama hasta la Regi&oacute;n de Los Lagos. Habita en una gran variedad de ambientes abiertos y semiabiertos, incluyendo praderas, pastizales, campos agr&iacute;colas, matorralales, bordes de caminos y bosques abiertos, desde el nivel del mar hasta altitudes de m&aacute;s de 2.000 metros en la precordillera.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"6,2,0\"><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Es un ave principalmente herb&iacute;vora, aliment&aacute;ndose de semillas (especialmente de gram&iacute;neas), brotes, hojas e insectos. Forrajea exclusivamente en el suelo, caminando lentamente entre la vegetaci&oacute;n. Son aves t&iacute;midas y elusivas, que prefieren correr o permanecer inm&oacute;viles y camufladas cuando se sienten amenazadas, antes de volar. Su vuelo es bajo y ruidoso, de corta duraci&oacute;n. Su canto es un \"uip-uip-uip\" melanc&oacute;lico y repetitivo, que a menudo se escucha al amanecer y al atardecer. Nidifican en el suelo, en una peque&ntilde;a depresi&oacute;n oculta entre la vegetaci&oacute;n.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"6,3,0\"><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\"> A nivel nacional en Chile, la p&eacute;rdida chilena est&aacute; clasificada como \"Preocupaci&oacute;n Menor\". Sin embargo, enfrenta amenazas como la p&eacute;rdida y fragmentaci&oacute;n de su h&aacute;bitat debido a la expansi&oacute;n agr&iacute;cola, la urbanizaci&oacute;n y el uso intensivo de pesticidas, as&iacute; como la depredaci&oacute;n por perros y gatos asilvestrados, y la caza deportiva no regulada en algunas zonas. A pesar de esto, su adaptabilidad a ambientes modificados por el hombre le ha permitido mantener poblaciones relativamente estables. Juega un papel ecol&oacute;gico en la dispersi&oacute;n de semillas y como presa para aves rapaces.</span></p>\r\n</li>\r\n</ul>', 'especies/36.png', 'activo', '2025-11-19 09:30:49', '2025-11-19 09:30:49', 5, 5),
(37, 'Geranoaetus melanoleucus ', 'geranoaetus-melanoleucus', 'Águila', 4, '<p data-path-to-node=\"0\"><em><span dir=\"auto\">Geranoaetus melanoleucus</span></em><span dir=\"auto\"> (&Aacute;guila Mora o &Aacute;guila Chilena) y una imagen realista, sin marco, adecuada para una tesis.</span></p>\r\n<hr data-path-to-node=\"1\">\r\n<p data-path-to-node=\"2\"><strong><span dir=\"auto\">Geranoaetus melanoleucus: El &Aacute;guila Mora, Imponente Rapaz de los Cielos Andinos y Patag&oacute;nicos</span></strong></p>\r\n<p data-path-to-node=\"3\"><span dir=\"auto\">La </span><strong><span dir=\"auto\">Geranoaetus melanoleucus</span></strong><span dir=\"auto\"> , conocida como &aacute;guila mora, &aacute;guila chilena o &aacute;guila geranoaeta, es una de las aves rapaces m&aacute;s grandes y majestuosas de Sudam&eacute;rica. Es un depredador clave en una vasta gama de ecosistemas chilenos, desde las altas cumbres andinas hasta la Patagonia, siendo un bioindicador de la salud ambiental de sus h&aacute;bitats.</span></p>\r\n<p data-path-to-node=\"4\"><strong><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul data-path-to-node=\"5\">\r\n<li>\r\n<p data-path-to-node=\"5,0,0\"><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es una rapaz de gran tama&ntilde;o (longitud de 70-90 cm; envergadura de 1,7-2 metros; peso de 2,5-4,5 kg). Los adultos presentan un plumaje inconfundible: dorso, cabeza y pecho superior de color gris-negruzco o pizarra, que contrasta fuertemente con el vientre y las \"pantaloneras\" de color blanco puro. La cola es relativamente corta y negra, con una banda terminal blanquecina. El pico es robusto y ganchudo, y sus patas son fuertes, de color amarillo, con poderosas garras. Los juveniles son completamente pardos o con la parte inferior estriada, adquiriendo el plumaje adulto despu&eacute;s de varios a&ntilde;os.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,1,0\"><strong><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n en Chile:</span></strong><span dir=\"auto\"> En Chile, el &aacute;guila mora se distribuye a lo largo de todo el territorio continental, desde la Regi&oacute;n de Arica y Parinacota hasta el Cabo de Hornos en Magallanes. Habita en una amplia diversidad de ecosistemas abiertos y semiabiertos, incluyendo zonas monta&ntilde;osas (cordillera de los Andes y de la Costa), estepas, matorrales, pastizales, valles y zonas costeras, siempre que haya acantilados o &aacute;rboles altos para nidificar.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,2,0\"><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Es un tope depredador, aliment&aacute;ndose de una variedad de mam&iacute;feros peque&ntilde;os y medianos (conejos, liebres, roedores, vizcachas, cr&iacute;as de guanaco y pud&uacute;), aves y reptiles. Caza principalmente planeando a gran altura y lanz&aacute;ndose en picada sobre sus presas. Son mon&oacute;gamos y territoriales, construyendo nidos voluminosos en acantilados o copas de &aacute;rboles altos. Su vuelo es potente y majestuoso, utilizando las corrientes t&eacute;rmicas para mantenerse en el aire con poco esfuerzo.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,3,0\"><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\"> A nivel global y nacional en Chile, el &aacute;guila mora est&aacute; clasificada como \"Preocupaci&oacute;n Menor\". Sin embargo, enfrenta amenazas como la persecuci&oacute;n humana (por conflictos con la ganader&iacute;a, aunque su dieta se centra m&aacute;s en conejos y liebres), la colisi&oacute;n con tendidos el&eacute;ctricos, la degradaci&oacute;n de su h&aacute;bitat y la disminuci&oacute;n de sus presas. Como &aacute;pice depredador, juega un papel crucial en la regulaci&oacute;n de las poblaciones de sus presas y en el mantenimiento de la salud de los ecosistemas donde habita.</span></p>\r\n</li>\r\n</ul>', 'especies/37.png', 'activo', '2025-11-19 09:32:22', '2025-11-19 09:32:22', 5, 5),
(38, 'Aphrastura masafuerae ', 'aphrastura-masafuerae', 'Rayadito de más afuera', 4, '<p data-path-to-node=\"0\"><em><span dir=\"auto\">Aphrastura masafuerae</span></em><span dir=\"auto\"> (Rayadito de M&aacute;s Afuera) y una imagen realista, sin marco, adecuada para una tesis.</span></p>\r\n<hr data-path-to-node=\"1\">\r\n<p data-path-to-node=\"2\"><strong><span dir=\"auto\">Aphrastura masafuerae: El Rayadito de M&aacute;s Afuera, Endemismo Cr&iacute;tico de la Isla Alejandro Selkirk</span></strong></p>\r\n<p data-path-to-node=\"3\"><span dir=\"auto\">La </span><strong><span dir=\"auto\">Aphrastura masafuerae</span></strong><span dir=\"auto\"> , conocida como rayadito de M&aacute;s Afuera o rayadito de Juan Fern&aacute;ndez, es un ave paseriforme de la familia Furnariidae, end&eacute;mica de la Isla Alejandro Selkirk (tambi&eacute;n conocida como Isla M&aacute;s Afuera) en el archipi&eacute;lago de Juan Fern&aacute;ndez, Chile. Es una de las aves m&aacute;s raras y cr&iacute;ticamente amenazadas del mundo, y un s&iacute;mbolo de la fragilidad de la biodiversidad insular.</span></p>\r\n<p data-path-to-node=\"4\"><strong><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul data-path-to-node=\"5\">\r\n<li>\r\n<p data-path-to-node=\"5,0,0\"><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es un ave peque&ntilde;a (longitud de 14-16 cm), de apariencia similar a otros rayaditos del continente, pero con caracter&iacute;sticas adaptadas a su h&aacute;bitat insular. Su plumaje es principalmente pardo-rojizo en el dorso, con un vientre m&aacute;s p&aacute;lido. Presenta un \"gorro\" rayado de pardo oscuro y claro, una ceja p&aacute;lida y una franja ocular oscura. La cola es relativamente larga, con plumas r&iacute;gidas y puntiagudas que le dan su nombre com&uacute;n de \"rayadito\", y que usa para apoyarse al trepar.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,1,0\"><strong><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n en Chile:</span></strong><span dir=\"auto\"> El rayadito de M&aacute;s Afuera es estrictamente end&eacute;mico de la Isla Alejandro Selkirk. Su h&aacute;bitat se restringe a los &uacute;ltimos remanentes de bosques de helechos y matorrales densos, especialmente en las quebradas y zonas m&aacute;s inaccesibles y h&uacute;medas de la isla, a altitudes medias y altas.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,2,0\"><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Es un ave insect&iacute;vora, aliment&aacute;ndose de peque&ntilde;os insectos y otros invertebrados que busca activamente entre la vegetaci&oacute;n densa, el sotobosque y la corteza de los &aacute;rboles. Es un ave muy activa y acrob&aacute;tica, que se mueve con destreza entre las ramas y el follaje. Su comportamiento reproductivo es poco conocido, pero se cree que nidifica en cavidades naturales o en la base de la vegetaci&oacute;n.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,3,0\"><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\"> El rayadito de M&aacute;s Afuera est&aacute; clasificado como \"En Peligro Cr&iacute;tico\" a nivel global y nacional. Es una de las aves m&aacute;s amenazadas del planeta, con una poblaci&oacute;n estimada en menos de 150 individuos. Sus principales y varias amenazas incluyen:</span></p>\r\n<ul data-path-to-node=\"5,3,1\">\r\n<li>\r\n<p data-path-to-node=\"5,3,1,0,0\"><strong><span dir=\"auto\">P&eacute;rdida y Degradaci&oacute;n de H&aacute;bitat:</span></strong><span dir=\"auto\"> La introducci&oacute;n de especies vegetales invasoras (eg, mora, maqui) que alteran la estructura del bosque nativo y compiten con la flora end&eacute;mica.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,3,1,1,0\"><strong><span dir=\"auto\">Depredaci&oacute;n por Especies Introducidas:</span></strong><span dir=\"auto\"> Gatos asilvestrados, ratas y cabras son depredadores y competidores voraces.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,3,1,2,0\"><strong><span dir=\"auto\">Incendios Forestales:</span></strong><span dir=\"auto\"> Los incendios en la isla representan una amenaza catastr&oacute;fica para una poblaci&oacute;n tan peque&ntilde;a y restringida. La extrema fragilidad de esta especie la convierte en un s&iacute;mbolo de la urgencia de conservaci&oacute;n de los ecosistemas insulares, que albergan una biodiversidad &uacute;nica y altamente vulnerable.</span></p>\r\n</li>\r\n</ul>\r\n</li>\r\n</ul>', 'especies/38.png', 'activo', '2025-11-19 09:33:52', '2025-11-19 09:33:52', 5, 5);
INSERT INTO `especies` (`esp_id`, `esp_nombre_cientifico`, `esp_slug`, `esp_nombre_comun`, `esp_tax_id`, `esp_descripcion`, `esp_imagen`, `esp_estado`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(39, 'Scelorchilus rubecula ', 'scelorchilus-rubecula', 'Chucao', 4, '<p data-path-to-node=\"2\"><strong><span dir=\"auto\">Scelorchilus rubecula: El Chucao, Ave Emblem&aacute;tica del Bosque Templado Lluvioso de Chile</span></strong></p>\r\n<p data-path-to-node=\"3\"><span dir=\"auto\">La </span><strong><span dir=\"auto\">Scelorchilus rubecula</span></strong><span dir=\"auto\"> , com&uacute;n conocida como chucao, es un ave paseriforme de la familia Rhinocryptidae, end&eacute;mica de los bosques templados de Chile y Argentina. Es una de las aves m&aacute;s distintivas y emblem&aacute;ticas de la selva valdiviana, reconocida por su llamativo canto y su comportamiento terrestre.</span></p>\r\n<p data-path-to-node=\"4\"><strong><span dir=\"auto\">Caracter&iacute;sticas clave:</span></strong></p>\r\n<ul data-path-to-node=\"5\">\r\n<li>\r\n<p data-path-to-node=\"5,0,0\"><strong><span dir=\"auto\">Morfolog&iacute;a:</span></strong><span dir=\"auto\"> Es un ave de tama&ntilde;o mediano (longitud de 18-19 cm), con un cuerpo robusto y patas fuertes adaptadas para moverse por el suelo. Su plumaje es inconfundible: la cabeza, el dorso y las alas son de color gris oscuro, mientras que el vientre es blanco puro. La caracter&iacute;stica m&aacute;s distintiva es el </span><strong><span dir=\"auto\">intenso color anaranjado-rojizo de su garganta, pecho y los flancos</span></strong><span dir=\"auto\"> , que se extiende hasta la nuca y el ojo. Posee una cola corta y una ceja (superciliar) blanca.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,1,0\"><strong><span dir=\"auto\">H&aacute;bitat y Distribuci&oacute;n en Chile:</span></strong><span dir=\"auto\"> En Chile, el chucao se distribuye desde la Regi&oacute;n del Maule hasta la Regi&oacute;n de Ays&eacute;n, incluyendo el archipi&eacute;lago de Chilo&eacute;. Habita exclusivamente en los densos bosques templados lluviosos (selva valdiviana) y matorrales densos, donde el sotobosque es abundante y h&uacute;medo. Prefiere zonas con mucha hojarasca y troncos ca&iacute;dos, donde busca su alimento.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,2,0\"><strong><span dir=\"auto\">Ecolog&iacute;a y Comportamiento:</span></strong><span dir=\"auto\"> Es un ave principalmente insect&iacute;vora, aliment&aacute;ndose de insectos, larvas, ara&ntilde;as y otros invertebrados que busca activamente entre la hojarasca del suelo, los troncos ca&iacute;dos y la vegetaci&oacute;n baja. Es un ave muy territorial, t&iacute;mida y de comportamiento terrestre, aunque puede volar distancias cortas para escapar de amenazas. Su canto es uno de los sonidos m&aacute;s caracter&iacute;sticos del bosque valdiviano: un potente y resonante \"&iexcl;chu-cau! &iexcl;chu-cau!\", que es una de las \"se&ntilde;ales\" m&aacute;s famosas de la cultura popular chilena para indicar buena o mala suerte seg&uacute;n de d&oacute;nde provenga el sonido. Nidifica en el suelo, en agujeros entre las ra&iacute;ces de los &aacute;rboles o debajo de troncos ca&iacute;dos.</span></p>\r\n</li>\r\n<li>\r\n<p data-path-to-node=\"5,3,0\"><strong><span dir=\"auto\">Estado de Conservaci&oacute;n y Relevancia:</span></strong><span dir=\"auto\"> A nivel global y nacional en Chile, el chucao est&aacute; clasificado como \"Preocupaci&oacute;n Menor\". Sin embargo, su dependencia de los bosques nativos lo hace sensible a la p&eacute;rdida y fragmentaci&oacute;n de su h&aacute;bitat debido a la deforestaci&oacute;n, los incendios y la expansi&oacute;n de monocultivos forestales. Su presencia es un indicador de la salud y madurez del sotobosque, y su canto es parte indisoluble de la experiencia en los bosques del sur de Chile, convirti&eacute;ndolo en un embajador de la biodiversidad de este ecosistema &uacute;nico.</span></p>\r\n</li>\r\n</ul>', 'especies/39.png', 'activo', '2025-11-19 09:34:34', '2025-11-19 09:34:56', 5, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `experiencias`
--

CREATE TABLE `experiencias` (
  `exp_id` int(10) UNSIGNED NOT NULL COMMENT 'ID de la experiencia',
  `exp_cargo` varchar(100) NOT NULL COMMENT 'Cargo de la experiencia',
  `exp_empresa` varchar(100) DEFAULT NULL COMMENT 'Empresa de la experiencia',
  `exp_fecha_inicio` date DEFAULT NULL COMMENT 'Fecha de inicio de la experiencia',
  `exp_fecha_fin` date DEFAULT NULL COMMENT 'Fecha de fin de la experiencia',
  `exp_descripcion` mediumtext DEFAULT NULL COMMENT 'Descripción de la experiencia',
  `exp_logros` mediumtext DEFAULT NULL COMMENT 'Logros alcanzados en la experiencia',
  `exp_publicada` enum('SI','NO') NOT NULL COMMENT 'Experiencia publicada',
  `exp_mod_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'Tipo de modalidad de Experiencia',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formacion`
--

CREATE TABLE `formacion` (
  `for_id` int(10) UNSIGNED NOT NULL COMMENT 'ID de la formación',
  `for_institucion` varchar(100) NOT NULL COMMENT 'Nombre de la institución',
  `for_grado_titulo` varchar(100) NOT NULL COMMENT 'Grado o título obtenido',
  `for_fecha_inicio` date DEFAULT NULL COMMENT 'Fecha de inicio',
  `for_fecha_fin` date DEFAULT NULL COMMENT 'Fecha de finalización',
  `for_logros_principales` mediumtext DEFAULT NULL COMMENT 'Logros principales',
  `for_tipo_logro` enum('Enseñanza','Licenciatura','Maestría','Doctorado','Diplomado','Certificación','Curso') NOT NULL COMMENT 'Tipo de logro',
  `for_categoria` enum('Curso','Certificación','Formación') NOT NULL COMMENT 'Corresponde a la categoría de la formación',
  `for_publicada` enum('SI','NO') NOT NULL COMMENT 'Indicador de publicación (SI/NO)',
  `for_codigo_validacion` varchar(255) DEFAULT NULL COMMENT 'Código de validación',
  `for_certificado` varchar(255) DEFAULT NULL COMMENT 'Ruta del archivo del certificado',
  `for_mostrar_certificado` enum('SI','NO') NOT NULL DEFAULT 'NO' COMMENT 'Indica si se debe mostrar el certificado',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `galerias`
--

CREATE TABLE `galerias` (
  `gal_id` int(10) UNSIGNED NOT NULL COMMENT 'Identificador único de la galería',
  `gal_tipo_registro` varchar(255) DEFAULT NULL COMMENT 'Tipo de registro asociado a la galería',
  `gal_id_registro` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del registro asociado a la galería',
  `gal_descripcion` mediumtext DEFAULT NULL COMMENT 'Descripción de la galería',
  `gal_estado` enum('publicado','borrador') DEFAULT 'borrador' COMMENT 'Estado de la galería (publicado/borrador)',
  `gal_titulo` varchar(255) DEFAULT NULL COMMENT 'Título de la galería',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `galerias`
--

INSERT INTO `galerias` (`gal_id`, `gal_tipo_registro`, `gal_id_registro`, `gal_descripcion`, `gal_estado`, `gal_titulo`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'especie', 37, NULL, 'borrador', 'Galería del Tipo de Registro: especie con ID = 37', '2025-11-19 09:32:24', '2025-11-19 09:32:24', 5, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habilidades`
--

CREATE TABLE `habilidades` (
  `hab_id` int(10) UNSIGNED NOT NULL COMMENT 'ID de la habilidad',
  `hab_nombre` varchar(100) NOT NULL COMMENT 'Nombre de la habilidad',
  `hab_nivel` int(3) UNSIGNED NOT NULL COMMENT 'Nivel de la habilidad',
  `hab_publicada` enum('SI','NO') NOT NULL COMMENT 'Estado de Publicación',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `herramientas`
--

CREATE TABLE `herramientas` (
  `her_id` int(10) UNSIGNED NOT NULL COMMENT 'ID de la herramienta',
  `her_nombre` varchar(100) NOT NULL COMMENT 'Nombre de la herramienta',
  `her_nivel` int(3) UNSIGNED NOT NULL COMMENT 'Nivel de la herramienta',
  `her_publicada` enum('SI','NO') NOT NULL COMMENT 'Estado de Publicación',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabla de Herramientas';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes_galeria`
--

CREATE TABLE `imagenes_galeria` (
  `img_id` int(10) UNSIGNED NOT NULL COMMENT 'Identificador único de la imagen',
  `img_gal_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID de la galería a la que pertenece la imagen',
  `img_ruta` varchar(255) DEFAULT NULL COMMENT 'Ruta de la imagen en el sistema de archivos o URL',
  `img_descripcion` mediumtext DEFAULT NULL COMMENT 'Descripción de la imagen',
  `img_estado` enum('publicado','borrador') DEFAULT 'publicado' COMMENT 'Estado de la imagen (publicado/borrador)',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `imagenes_galeria`
--

INSERT INTO `imagenes_galeria` (`img_id`, `img_gal_id`, `img_ruta`, `img_descripcion`, `img_estado`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 1, '', '', 'publicado', '2025-11-19 09:32:39', '2025-11-19 09:32:39', 5, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `layouts`
--

CREATE TABLE `layouts` (
  `lay_id` int(10) UNSIGNED NOT NULL,
  `lay_nombre` varchar(100) NOT NULL COMMENT 'Nombre del layout',
  `lay_ruta_imagenes` varchar(255) DEFAULT NULL COMMENT 'Ruta de las imágenes',
  `lay_estado` enum('activo','inactivo') NOT NULL COMMENT 'Estado del layout (activo/inactivo)',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabla para almacenar información sobre los layouts disponibles en la aplicación.';

--
-- Volcado de datos para la tabla `layouts`
--

INSERT INTO `layouts` (`lay_id`, `lay_nombre`, `lay_ruta_imagenes`, `lay_estado`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'Default', 'DEFAULT', 'activo', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lecciones`
--

CREATE TABLE `lecciones` (
  `lec_id` int(10) UNSIGNED NOT NULL COMMENT 'ID único de la lección',
  `lec_titulo` varchar(255) NOT NULL COMMENT 'Título de la lección',
  `lec_contenido` longtext DEFAULT NULL COMMENT 'Contenido de la lección',
  `lec_tipo` enum('texto','video','ejercicio','codigo') NOT NULL DEFAULT 'texto' COMMENT 'Tipo de contenido',
  `lec_orden` int(10) UNSIGNED DEFAULT 0 COMMENT 'Orden dentro del módulo',
  `lec_estado` enum('borrador','publicado') NOT NULL DEFAULT 'borrador' COMMENT 'Estado de la lección',
  `lec_slug` mediumtext NOT NULL COMMENT 'Slug de la lección',
  `lec_imagen` varchar(255) DEFAULT NULL COMMENT 'Imagen destacada de la lección',
  `lec_mod_id` int(10) UNSIGNED NOT NULL COMMENT 'ID del módulo al que pertenece la lección',
  `lec_icono` varchar(255) DEFAULT NULL COMMENT 'Ícono representativo de la lección',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `media`
--

CREATE TABLE `media` (
  `med_id` int(10) UNSIGNED NOT NULL COMMENT 'Identificador único del medio',
  `med_nombre` varchar(255) NOT NULL COMMENT 'Nombre del medio',
  `med_ruta` varchar(255) NOT NULL COMMENT 'Ruta del medio en el servidor',
  `med_descripcion` varchar(255) NOT NULL COMMENT 'Descripción del medio',
  `med_entidad` varchar(50) DEFAULT NULL COMMENT 'Entidad asociada al medio',
  `med_registro` int(11) DEFAULT NULL COMMENT 'ID del registro asociado (p.ej. pag_id, art_id, etc.)',
  `med_orden` int(11) NOT NULL DEFAULT 0 COMMENT 'Correlativo (MAX+1) por med_entidad,med_tipo,med_registro',
  `med_tipo` enum('entidad','site','tinymce') NOT NULL DEFAULT 'entidad' COMMENT 'Tipo de uso del medio (entidad=contenido, site=global, tinymce=WYSIWYG)',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabla para gestionar medios limitados (imágenes, videos, archivos, etc.) en la aplicación.';

--
-- Volcado de datos para la tabla `media`
--

INSERT INTO `media` (`med_id`, `med_nombre`, `med_ruta`, `med_descripcion`, `med_entidad`, `med_registro`, `med_orden`, `med_tipo`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'logo_ecolens', 'default/site/logo_ecolens.png', 'Logo Oficial de Ecolens', '', NULL, 0, 'site', '1900-01-01 00:00:00', '2025-10-10 17:22:29', 1, 1),
(2, 'favicon', 'default/site/sin_imagen.png', 'Favicon del sitio', '', NULL, 0, 'site', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(3, 'favicon_apple', 'default/site/favicon_apple.png', 'Favicon para Apple', NULL, NULL, 0, 'site', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(4, 'logo', 'default/site/logo.png', 'Logo o imagen de Perfil de la persona o empresa', NULL, NULL, 0, 'site', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(5, 'imagen_cliente', 'default/entidad/cliente/sin_imagen.png', 'Foto por defecto de clientes de la empresa', 'cliente', NULL, 0, 'entidad', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(6, 'imagen_proyectos', 'default/entidad/proyecto/sin_imagen.png', 'Foto por defecto de proyectos de la empresa', 'proyecto', NULL, 0, 'entidad', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(7, 'imagen_articulo', 'default/entidad/articulo/sin_imagen.png', 'Foto por defecto de Artículos', 'articulo', NULL, 0, 'entidad', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(8, 'imagen_trabajador', 'default/entidad/trabajador/sin_imagen.png', 'Imagen por defecto en trabajadores', 'trabajador', NULL, 0, 'entidad', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(9, 'imagen_curso', 'default/entidad/curso/sin_imagen.png', 'Imagen por defecto en Cursos', 'curso', NULL, 0, 'entidad', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(10, 'imagen_modulo', 'default/entidad/modulo/sin_imagen.png', 'Imagen por defecto en Módulos', 'modulo', NULL, 0, 'entidad', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(11, 'imagen_leccion', 'default/entidad/leccion/sin_imagen.png', 'Imagen por defecto en Lecciones', 'leccion', NULL, 0, 'entidad', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(12, 'imagen_recurso', 'default/entidad/recurso/sin_imagen.png', 'Imagen por defecto en Recursos', 'recurso', NULL, 0, 'entidad', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(13, 'imagen_servicios', 'default/entidad/servicio/sin_imagen.png', 'Foto por defecto de servicios de la empresa', 'servicio', NULL, 0, 'entidad', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `men_id` int(10) UNSIGNED NOT NULL COMMENT 'Identificador único del menú',
  `men_nombre` varchar(255) NOT NULL COMMENT 'Nombre del menú',
  `men_url` varchar(255) DEFAULT NULL COMMENT 'URL del menú',
  `men_etiqueta` varchar(255) NOT NULL COMMENT 'Etiqueta del menú',
  `men_mostrar` enum('Si','No') DEFAULT 'Si' COMMENT 'Indica si el menú debe mostrarse o no',
  `men_nivel` enum('nivel_1','nivel_2') DEFAULT 'nivel_1' COMMENT 'Nivel del menú (nivel_1 para el menú principal, nivel_2 para submenús)',
  `men_link_options` varchar(1000) DEFAULT NULL COMMENT 'Opciones adicionales del enlace del menú (por ejemplo, estilos)',
  `men_target` enum('_blank','_self','_parent','_top') DEFAULT '_self' COMMENT 'Atributo target del enlace del menú',
  `men_rol_id` int(10) NOT NULL COMMENT 'ID del rol asociado al menú (clave externa que referencia la tabla de roles)',
  `men_padre_id` int(10) UNSIGNED DEFAULT NULL,
  `men_posicion` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Posición del menú dentro de su nivel 1',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro',
  `men_icono` varchar(100) NOT NULL DEFAULT 'bi-house|#223142' COMMENT 'Ícono Bootstrap + color (ej: bi-house|#1abc9c)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ID del menú padre para las opciones de nivel 2';

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`men_id`, `men_nombre`, `men_url`, `men_etiqueta`, `men_mostrar`, `men_nivel`, `men_link_options`, `men_target`, `men_rol_id`, `men_padre_id`, `men_posicion`, `created_at`, `updated_at`, `created_by`, `updated_by`, `men_icono`) VALUES
(1, 'Index', 'site/index', 'Inicio', 'Si', 'nivel_1', '', '_self', 3, NULL, 1, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-house-door|#1bc3d4'),
(2, 'Contenido de mi sitio web', '', 'Contenido de mi sitio web', 'Si', 'nivel_1', '', '_self', 3, NULL, 13, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-window|#2ecc71'),
(3, 'Clientes', 'cliente/index', 'Clientes', 'No', 'nivel_2', '', '_self', 3, 2, 16, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-people|#2980b9'),
(4, 'Mi Ficha personal', '', '', 'No', 'nivel_1', '', '_self', 3, NULL, 26, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-person-badge|#d35400'),
(5, 'Trabajadores', 'trabajador/index', 'Ver mis Trabajadores', 'Si', 'nivel_2', '', '_self', 3, 48, 41, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-person-lines-fill|#f39c12'),
(6, 'Portafolio de Proyectos', 'proyecto/index', 'Portafolio de Proyectos', 'Si', 'nivel_2', '', '_self', 3, 48, 42, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-kanban|#16a085'),
(7, 'Configurar sitio web', '', '', 'Si', 'nivel_1', '', '_self', 3, NULL, 31, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-sliders|#8e44ad'),
(8, 'Administración Root', '', '', 'Si', 'nivel_1', '', '_self', 1, NULL, 50, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-shield-lock|#e67e22'),
(9, 'Experiencias', 'experiencia/index', '', 'No', 'nivel_2', '', '_self', 3, 2, 17, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-briefcase|#c0392b'),
(10, 'Herramientas', 'herramienta/index', '', 'No', 'nivel_2', '', '_self', 3, 2, 18, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-tools|#34495e'),
(11, 'Habilidades', 'habilidad/index', '', 'No', 'nivel_2', '', '_self', 3, 2, 19, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-star|#f1c40f'),
(12, 'Formación Académica', 'formacion/index', '', 'No', 'nivel_2', '', '_self', 3, 2, 20, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-mortarboard|#9b59b6'),
(13, 'Servicios', 'servicio/index', '', 'No', 'nivel_2', '', '_self', 3, 2, 21, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-gear|#8e44ad'),
(14, 'Redes Sociales', 'red/index', '', 'Si', 'nivel_2', '', '_self', 3, 2, 22, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-share|#2980b9'),
(15, 'Correos electrónicos', 'correo/index', '', 'Si', 'nivel_2', '', '_self', 3, 2, 23, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-envelope|#e67e22'),
(16, 'Páginas', 'pagina/index', '', 'Si', 'nivel_2', '', '_self', 3, 2, 14, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-person-workspace|#223142'),
(17, 'Ver mi Perfil', 'perfil/profile', '', 'Si', 'nivel_2', '', '_self', 3, 4, 27, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-file-earmark-person|#e67e22'),
(18, 'Visualizar mi sitio', '../../sitio/web', '', 'Si', 'nivel_2', '', '_blank', 3, 37, 49, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-globe|#16a085'),
(19, 'Antecedentes Generales', 'biografia/ficha', '', 'No', 'nivel_2', '', '_self', 3, 45, 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-house|#223142'),
(20, 'Visualizar mi CV', 'curriculum/visualizar', '', 'No', 'nivel_2', '', '_self', 3, 4, 28, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-file-earmark-person|#e67e22'),
(21, 'Visualizar mi sitio', '../../sitio/web', '', 'No', 'nivel_2', '', '_blank', 3, 45, 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-house|#223142'),
(22, 'Fotografias del Sitio', 'media/index', '', 'Si', 'nivel_2', '', '_self', 3, 7, 34, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-images|#27ae60'),
(25, 'Newsletter', 'newsletter/index', 'Suscritos al Newsletter', 'No', 'nivel_2', '', '_self', 3, 2, 24, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-send|#1bc3d4'),
(26, 'Colores del Sitio', 'color/index', 'Colores del Sitio', 'Si', 'nivel_2', '', '_self', 3, 7, 35, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-palette|#bb4d1e'),
(27, 'Modalidad de las Experiencias', 'modalidad/index', '', 'No', 'nivel_2', '', '_self', 3, 7, 36, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-collection|#6f42c1'),
(28, 'Categorías de Servicios', 'categoria-servicio/index', '', 'No', 'nivel_2', '', '_self', 3, 7, 37, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-tags|#16a085'),
(29, 'Asuntos de Formularios de contacto', 'asunto/index', '', 'Si', 'nivel_2', '', '_self', 3, 7, 38, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-envelope-paper|#c0392b'),
(30, 'Roles de Seguridad', 'rol/index', '', 'Si', 'nivel_2', '', '_self', 1, 8, 54, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-person-check|#e67e22'),
(31, 'Usuarios del Sistema', 'user/index', '', 'Si', 'nivel_2', '', '_self', 1, 8, 55, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-person-bounding-box|#2980b9'),
(32, 'Yii Crud', '/gii', '', 'Si', 'nivel_2', '', '_blank', 3, 8, 56, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-braces|#7f8c8d'),
(34, 'Estructura de Tablas', 'root/tablas', '', 'Si', 'nivel_2', '', '_self', 3, 8, 57, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-table|#6c757d'),
(35, 'Consultas SQL', 'root/consultasql', '', 'Si', 'nivel_2', '', '_self', 1, 8, 58, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-search|#bb4d1e'),
(36, 'Administrar Menú de Usuarios', 'menu/index', '', 'Si', 'nivel_2', '', '_self', 1, 8, 59, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-menu-app|#1abc9c'),
(37, 'Links Externos', '', '', 'No', 'nivel_1', '', '_self', 3, NULL, 48, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-link-45deg|#27ae60'),
(38, 'Layout', 'layout/index', 'Layout del sitio', 'Si', 'nivel_2', '', '_self', 1, 8, 60, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-layout-text-window|#c0392b'),
(39, 'Tema del sitio', 'layout/tema', '', 'Si', 'nivel_2', '', '_self', 3, 2, 25, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-droplet|#2980b9'),
(40, 'Artículos', 'articulo/index', '', 'No', 'nivel_2', '', '_self', 3, 2, 15, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-newspaper|#e67e22'),
(41, 'Categorías de Artículos', 'categoria-articulo/index', '', 'No', 'nivel_2', '', '_self', 3, 7, 39, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-tags|#16a085'),
(42, 'E-learning', '', 'E-learning', 'No', 'nivel_1', '', '_self', 3, NULL, 43, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-mortarboard|#9b59b6'),
(43, 'Cursos', 'curso/index', 'Cursos', 'Si', 'nivel_2', '', '_self', 3, 42, 44, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-journal-bookmark|#9b59b6'),
(44, 'Módulos', 'modulo/index', 'Módulos', 'Si', 'nivel_2', '', '_self', 3, 42, 45, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-collection-play|#1bc3d4'),
(45, 'Lecciones', 'leccion/index', 'Lecciones', 'Si', 'nivel_2', '', '_self', 3, 42, 46, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-book|#2980b9'),
(46, 'Recursos', 'recurso/index', 'Recursos', 'Si', 'nivel_2', '', '_self', 3, 42, 47, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-file-earmark-zip|#34495e'),
(47, 'Administrar mi CV', 'curriculum/administrar', 'Administrar mi CV', 'No', 'nivel_2', '', '_self', 3, 4, 29, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-clipboard-data|#223142'),
(48, 'Mi Empresa', '', '', 'No', 'nivel_1', '', '_self', 3, NULL, 40, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-building|#16a085'),
(49, 'Testimonios', 'testimonio/index', '', 'No', 'nivel_2', '', '_self', 3, 4, 30, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-chat-quote|#16a085'),
(50, 'Status del Sistema', 'root/status', '', 'Si', 'nivel_2', '', '_self', 3, 8, 61, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-activity|#e67e22'),
(51, 'Colores del CMS', 'opcion/cms', '', 'Si', 'nivel_2', '', '_self', 3, 8, 62, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-palette2|#bb4d1e'),
(52, 'API Explorer', 'api-explorer/index', '', 'Si', 'nivel_2', '', '_self', 3, 8, 53, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-cloud-check|#2980b9'),
(53, 'Fotografías de TinyMCE', 'media/tinymce', '', 'Si', 'nivel_2', '', '_self', 3, 7, 33, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-images|#27ae60'),
(54, 'Opciones del sitio', 'opcion/index', '', 'Si', 'nivel_2', '', '_self', 3, 7, 32, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-tools|#bb4d1e'),
(55, 'Categorías de Opciones', 'categoria-opcion/index', '', 'Si', 'nivel_2', '', '_self', 1, 8, 52, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-tag|#e67e22'),
(56, 'Configurar Actividades', 'actividad/configurar', '', 'Si', 'nivel_2', '', '_self', 3, 8, 51, '1900-01-01 00:00:00', '2025-12-03 20:45:07', 1, 1, 'bi-activity|#ec31e8'),
(57, 'Ecolens', '', '', 'Si', 'nivel_1', '', '_self', 3, NULL, 2, '2025-10-15 20:10:00', '2025-12-03 20:45:07', 1, 1, 'bi-filter-circle|#feffff'),
(58, 'Detecciones', 'deteccion/index', '', 'Si', 'nivel_2', '', '_self', 3, 57, 5, '2025-10-15 20:11:26', '2025-12-03 20:45:07', 1, 1, 'bi-zoom-in|#ff2600'),
(59, 'Dispositivos', 'dispositivo/index', '', 'No', 'nivel_2', '', '_self', 3, 57, 7, '2025-10-15 20:12:56', '2025-12-03 20:45:07', 1, 1, 'bi-pc|#00f900'),
(60, 'Especies', 'especie/index', '', 'Si', 'nivel_2', '', '_self', 3, 57, 4, '2025-10-15 20:17:14', '2025-12-03 20:45:07', 1, 1, 'bi-bug|#ff9200'),
(61, 'Observadores', 'observador/index', '', 'Si', 'nivel_2', '', '_self', 3, 57, 6, '2025-10-15 20:18:43', '2025-12-03 20:45:07', 1, 1, 'bi-people-fill|#009192'),
(62, 'Taxonomías', 'taxonomia/index', '', 'Si', 'nivel_2', '', '_self', 3, 57, 3, '2025-10-15 20:23:13', '2025-12-03 20:45:07', 1, 1, 'bi-house-add|#935100'),
(63, 'Dashboard general', 'monitoreo/index', '', 'Si', 'nivel_2', '', '_self', 3, 64, 9, '2025-10-26 00:23:22', '2025-12-03 20:53:40', 1, 1, 'bi-bar-chart-fill|#03c200'),
(64, 'Monitoreo y Telemetría', '', '', 'Si', 'nivel_1', '', '_self', 3, NULL, 8, '2025-12-03 20:01:43', '2025-12-03 20:45:07', 1, 1, 'bi-thermometer-half|#ffdd00'),
(65, 'Dashboard usuarios', 'monitoreo/usuarios', '', 'Si', 'nivel_2', '', '_self', 3, 64, 10, '2025-12-03 20:56:52', '2025-12-03 20:56:52', 1, 1, 'bi-person-workspace|#13a050'),
(66, 'Dashboard sistema', 'monitoreo/sistema', '', 'Si', 'nivel_2', '', '_self', 3, 64, 11, '2025-12-03 20:58:09', '2025-12-03 21:03:57', 1, 1, 'bi-pc-display-horizontal|#b5b691'),
(67, 'Dashboard API', 'monitoreo/api', '', 'Si', 'nivel_2', '', '_self', 3, 64, 12, '2025-12-03 21:01:02', '2025-12-03 21:02:57', 1, 1, 'bi-cpu|#dfa9a9');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modalidad`
--

CREATE TABLE `modalidad` (
  `mod_id` int(10) UNSIGNED NOT NULL COMMENT 'Identificador único de modalidad',
  `mod_nombre` varchar(100) NOT NULL COMMENT 'Nombre de la modalidad de trabajo',
  `mod_publicado` enum('SI','NO') NOT NULL COMMENT 'Indica si la modalidad está publicada',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `modalidad`
--

INSERT INTO `modalidad` (`mod_id`, `mod_nombre`, `mod_publicado`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'Fulltime', 'SI', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(2, 'Partime', 'SI', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(3, 'Emprendimiento', 'SI', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modelos`
--

CREATE TABLE `modelos` (
  `mod_id` int(10) UNSIGNED NOT NULL COMMENT 'Identificador único del modelo IA',
  `mod_nombre` varchar(100) NOT NULL COMMENT 'Nombre del modelo (ej. EfficientNet-B5)',
  `mod_version` varchar(50) DEFAULT NULL COMMENT 'Versión o checkpoint interno del modelo',
  `mod_archivo` varchar(255) DEFAULT NULL COMMENT 'Ruta o nombre del archivo .pth del modelo',
  `mod_dataset` varchar(255) DEFAULT NULL COMMENT 'Dataset utilizado para el entrenamiento',
  `mod_precision_val` decimal(5,2) DEFAULT NULL COMMENT 'Precisión de validación en porcentaje',
  `mod_fecha_entrenamiento` datetime DEFAULT current_timestamp() COMMENT 'Fecha de entrenamiento del modelo',
  `mod_estado` enum('activo','deprecado','en_entrenamiento') DEFAULT 'activo' COMMENT 'Estado operativo del modelo',
  `mod_notas` text DEFAULT NULL COMMENT 'Observaciones o comentarios técnicos',
  `mod_tipo` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Modelos de inteligencia artificial entrenados en EcoLens.';

--
-- Volcado de datos para la tabla `modelos`
--

INSERT INTO `modelos` (`mod_id`, `mod_nombre`, `mod_version`, `mod_archivo`, `mod_dataset`, `mod_precision_val`, `mod_fecha_entrenamiento`, `mod_estado`, `mod_notas`, `mod_tipo`) VALUES
(1, 'efficientnet_b5_best.pth', NULL, NULL, NULL, NULL, '2025-10-19 15:11:39', 'activo', NULL, 'experto'),
(2, 'modelo_experto_mamiferos.pth', NULL, NULL, NULL, NULL, '2025-10-19 16:08:13', 'activo', NULL, 'experto'),
(3, 'demo_router', NULL, NULL, NULL, NULL, '2025-10-23 10:19:44', 'activo', NULL, 'router'),
(4, 'demo_expert', NULL, NULL, NULL, NULL, '2025-10-23 10:19:44', 'activo', NULL, 'experto'),
(5, 'efficientnet_b5', NULL, NULL, NULL, NULL, '2025-10-25 01:30:53', 'activo', NULL, 'router'),
(6, 'desconocido', NULL, NULL, NULL, NULL, '2025-10-25 03:23:59', 'activo', NULL, 'experto'),
(7, 'EfficientNet-B5', NULL, NULL, NULL, NULL, '2025-11-12 23:57:31', 'activo', NULL, 'router'),
(8, 'Aves-v1', NULL, NULL, NULL, NULL, '2025-11-12 23:57:31', 'activo', NULL, 'experto'),
(9, 'modelo_experto_aves.pth', NULL, NULL, NULL, NULL, '2025-11-18 22:19:05', 'activo', NULL, 'experto');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

CREATE TABLE `modulos` (
  `mod_id` int(10) UNSIGNED NOT NULL COMMENT 'ID único del módulo',
  `mod_titulo` varchar(255) NOT NULL COMMENT 'Título del módulo',
  `mod_descripcion` mediumtext DEFAULT NULL COMMENT 'Descripción del módulo',
  `mod_orden` int(10) UNSIGNED DEFAULT 0 COMMENT 'Orden del módulo dentro del curso',
  `mod_estado` enum('borrador','publicado') NOT NULL DEFAULT 'borrador' COMMENT 'Estado del módulo',
  `mod_slug` mediumtext NOT NULL COMMENT 'Slug del módulo',
  `mod_imagen` varchar(255) DEFAULT NULL COMMENT 'Imagen de portada del módulo',
  `mod_cur_id` int(10) UNSIGNED NOT NULL COMMENT 'ID del curso al que pertenece el módulo',
  `mod_icono` varchar(255) DEFAULT NULL COMMENT 'Ícono representativo del módulo',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `newsletter`
--

CREATE TABLE `newsletter` (
  `new_id` int(10) UNSIGNED NOT NULL COMMENT 'ID único del suscriptor',
  `new_email` varchar(255) NOT NULL COMMENT 'Correo del suscriptor',
  `new_estado` enum('suscrito','dado_de_baja','pendiente','bloqueado') NOT NULL DEFAULT 'pendiente' COMMENT 'Estado de la suscripción',
  `new_verificado` enum('SI','NO') NOT NULL DEFAULT 'NO',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabla para almacenar registros de suscriptores del newsletter';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `observadores`
--

CREATE TABLE `observadores` (
  `obs_id` int(10) UNSIGNED NOT NULL COMMENT 'Identificador único del observador o usuario de EcoLens',
  `obs_nombre` varchar(150) NOT NULL COMMENT 'Nombre completo del observador',
  `obs_email` varchar(150) NOT NULL COMMENT 'Correo electrónico del observador',
  `obs_usuario` varchar(100) DEFAULT NULL COMMENT 'Nombre de usuario',
  `obs_institucion` varchar(150) DEFAULT NULL COMMENT 'Institución o afiliación del observador',
  `obs_experiencia` enum('principiante','aficionado','experto','institucional') DEFAULT 'principiante' COMMENT 'Nivel de experiencia declarado por el observador',
  `obs_pais` varchar(100) DEFAULT NULL COMMENT 'País de residencia o registro',
  `obs_ciudad` varchar(100) DEFAULT NULL COMMENT 'Ciudad o región del observador',
  `obs_estado` enum('activo','inactivo','pendiente') DEFAULT 'activo' COMMENT 'Estado de la cuenta del observador',
  `obs_fecha_registro` datetime DEFAULT current_timestamp() COMMENT 'Fecha y hora de registro del observador',
  `obs_foto` varchar(255) DEFAULT NULL COMMENT 'Imagen o avatar del observador',
  `created_at` datetime DEFAULT current_timestamp() COMMENT 'Fecha de creación del registro',
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Fecha de última actualización del registro',
  `obs_token` varchar(255) DEFAULT NULL COMMENT 'Hash de contraseña del observador',
  `obs_act_token_hash` varchar(64) DEFAULT NULL COMMENT 'Hash del token de activación',
  `obs_act_expires` datetime DEFAULT NULL COMMENT 'Fecha/hora de expiración del token de activación',
  `obs_email_verificado_at` datetime DEFAULT NULL COMMENT 'Marca de verificación de correo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Usuarios del dominio EcoLens que generan detecciones o aportes de observación.';

--
-- Volcado de datos para la tabla `observadores`
--

INSERT INTO `observadores` (`obs_id`, `obs_nombre`, `obs_email`, `obs_usuario`, `obs_institucion`, `obs_experiencia`, `obs_pais`, `obs_ciudad`, `obs_estado`, `obs_fecha_registro`, `obs_foto`, `created_at`, `updated_at`, `obs_token`, `obs_act_token_hash`, `obs_act_expires`, `obs_email_verificado_at`) VALUES
(1, 'Rogelio Muñoz', 'rmunoz1612@gmail.com', 'rmunozm', 'Universidad Mayor', 'institucional', 'Chile', 'Metropolitana', 'activo', '2025-10-18 02:48:56', 'observadores/4.jpg', '2025-10-18 02:48:56', '2025-10-29 09:32:07', '$2y$10$PJGjAvo5GWbuKG4kRnCr5uuZqN9xEtbWlnKtBBLxDlUrlbDxzRocq', NULL, NULL, NULL),
(2, 'ROGELIO ERNESTO MUÑOZ MUÑOZ', 'rogeliomunozmunoz@outlook.com', 'rogmunoz', 'rogmunoz', 'principiante', 'Chile', 'Metropolitana', 'activo', '2025-10-27 02:27:33', NULL, '2025-10-27 02:27:33', '2025-10-28 21:55:29', '$2y$10$.9N/YDi5XRwvo6gcV85B7OnTUJv61In0gjDogrF1ick54pRcJ64e6', NULL, NULL, '2025-10-27 02:32:49'),
(3, 'valeria paz soriano', 'valeriapaz.sf@gmail.com', 'valeriapaz.sfgmail.com', NULL, 'principiante', 'Chile', NULL, 'activo', '2025-10-28 21:56:00', NULL, '2025-10-28 21:56:00', '2025-10-28 21:56:47', '$2y$10$/30VAlwYbJeQQuIlzYnjHeeadQnhWsod0NgjfjL8bJtctg7bT/m4C', NULL, NULL, '2025-10-28 21:56:47'),
(4, 'Nicole Aranda', 'nicole.aranda@live.cl', 'nicole.aranda_16', NULL, 'principiante', 'Chile', NULL, 'activo', '2025-10-30 11:07:03', NULL, '2025-10-30 11:07:03', '2025-10-30 11:07:53', '$2y$10$qwqc9ldQ2Z.rLmNJhTNuyeLL5DsT6hryRdDzF38FNiNC68ailCGge', NULL, NULL, '2025-10-30 11:07:53'),
(5, 'Matías', 'matiasdelmaipo@gmail.com', 'matiasdelmaipo.21', NULL, 'aficionado', 'Chile', NULL, 'activo', '2025-10-31 14:04:52', NULL, '2025-10-31 14:04:52', '2025-10-31 14:06:32', '$2y$10$8V/9HpiyuY4j47ecdKSFkOIxMftDiTwew/NZ7L.PzKa39Q7zl6W4C', NULL, NULL, '2025-10-31 14:06:32'),
(6, 'Ninoska Aracelli Pino', 'ninoska.aracelli@gmail.com', 'ninoska', NULL, 'principiante', 'Chile', 'Metropolitana', 'activo', '2025-11-11 21:50:40', NULL, '2025-11-11 21:50:40', '2025-11-11 21:51:03', '$2y$10$C1a83LE9ix.Ti94ZWGqkQul9OPckixJN8wSq3.neLYnFzbCTO4R7C', NULL, NULL, '2025-11-11 21:51:03'),
(7, 'Matias Aguilar', 'diegojeriab@gmail.com', 'abusamadres', NULL, 'institucional', 'Chile', 'Metropolitana', 'activo', '2025-11-17 22:02:55', NULL, '2025-11-17 22:02:55', '2025-11-17 22:03:12', '$2y$10$B47fSwZ8NivBOX.TBWsES.zFJjprq8UjwvkvAFMOnCi/xZakwnT7S', NULL, NULL, '2025-11-17 22:03:12'),
(8, 'Vanessa Macarena Escobar Illilef', 'vanessa.macarena.escobar@gmail.com', 'vane.escobar90', NULL, 'principiante', 'Chile', 'Magallanes', 'activo', '2025-11-19 21:40:05', NULL, '2025-11-19 21:40:05', '2025-11-19 21:42:54', '$2y$10$SCGbr1IQDjOINFvHoysnS.3W9.ZS6.rvqyjYqHhoksgsttNWAHDki', NULL, NULL, '2025-11-19 21:42:54'),
(9, 'Brandon Macías González', 'bmacias@fen.uchile.cl', 'bmacias', 'Universidad de Chile', 'principiante', 'Chile', NULL, 'activo', '2025-11-19 23:12:41', NULL, '2025-11-19 23:12:41', '2025-11-19 23:13:14', '$2y$10$pkFkNt2pL8SZEIGIL/I1h.coIPMV0anGpW3JU.fPIFqck5VaDWdJ6', NULL, NULL, '2025-11-19 23:13:14'),
(10, 'Daniel Leguizamo', 'daniel.leguizamo@mail.udp.cl', 'dleguizamo', 'Universidad diego portales', 'principiante', 'Chile', 'Metropolitana', 'activo', '2025-11-19 23:49:02', NULL, '2025-11-19 23:49:02', '2025-11-19 23:50:49', '$2y$10$yAOyKn4VYJFBAx3Fk.Qa.u7I5u3kfeceLdCJOZgYjexB0fNMyntMC', NULL, NULL, '2025-11-19 23:50:49'),
(11, 'Aurelien FLON', 'aurelienflon@gmail.com', 'aurefrancia', NULL, 'aficionado', 'Otro', NULL, 'activo', '2025-11-19 23:58:05', NULL, '2025-11-19 23:58:05', '2025-11-19 23:58:21', '$2y$10$YvLOzQMRCk37dU.TKM0TxOhzleFPvGCAS5SpQ.EJ75Iu1UlDx2BGS', NULL, NULL, '2025-11-19 23:58:21'),
(12, 'Felipe ignacio patuelli arenas', 'patuelli.oc@gmail.com', 'felipe.patuelli', NULL, 'principiante', 'Chile', 'Antofagasta', 'activo', '2025-11-20 07:40:45', NULL, '2025-11-20 07:40:45', '2025-11-20 07:41:22', '$2y$10$PMrKtwYv3SkwsDNci3QgoOVX87AQj6Panj1jLHDC1Yn8Ux6ve0Yf6', NULL, NULL, '2025-11-20 07:41:22'),
(13, 'Perico los palotes', 'youcanthandlemelol@gmail.com', 'perico123', NULL, 'principiante', 'Chile', 'Valparaíso', 'activo', '2025-11-20 09:35:15', NULL, '2025-11-20 09:35:15', '2025-11-20 09:36:07', '$2y$10$EyLZ6HlbRLtsYZ8c3ccZ8Od7WteQ6pH2v5cXn3rro0iiD0MRdikQi', NULL, NULL, '2025-11-20 09:36:07'),
(14, 'Javiera Calderon', 'javiera.herrera.c26@gmail.com', 'javiera.herrera.c26gmail.com', NULL, 'principiante', 'Chile', NULL, 'activo', '2025-11-21 17:16:33', NULL, '2025-11-21 17:16:33', '2025-11-21 17:17:08', '$2y$10$.q/938jUwJyUFd2HdAylnO.9TOKGZbtekHMNDBGRoTUi1Vg6XVpum', NULL, NULL, '2025-11-21 17:17:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opciones`
--

CREATE TABLE `opciones` (
  `opc_id` int(10) UNSIGNED NOT NULL COMMENT 'Identificador único de la opción',
  `opc_nombre` varchar(255) NOT NULL COMMENT 'Nombre único y descriptivo de la opción (ej: visual_color_primario)',
  `opc_valor` text DEFAULT NULL COMMENT 'Valor actual de la opción',
  `opc_tipo` enum('string','int','bool','float','json','enum','color') NOT NULL DEFAULT 'string' COMMENT 'Tipo de dato de la opción',
  `opc_cat_id` int(10) UNSIGNED NOT NULL COMMENT 'ID de la categoría funcional a la que pertenece (categorias_opciones.cat_id)',
  `opc_rol_id` int(10) UNSIGNED NOT NULL COMMENT 'ID mínimo de rol requerido para modificar la opción (roles.rol_id)',
  `opc_descripcion` varchar(255) DEFAULT NULL COMMENT 'Descripción breve del propósito y uso de la opción',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha de creación del registro',
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Fecha de última modificación',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó la opción',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que modificó por última vez la opción'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Opciones centralizadas del sistema, referenciadas por categoría y rol mínimo para edición';

--
-- Volcado de datos para la tabla `opciones`
--

INSERT INTO `opciones` (`opc_id`, `opc_nombre`, `opc_valor`, `opc_tipo`, `opc_cat_id`, `opc_rol_id`, `opc_descripcion`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'debug', 'yes', 'bool', 1, 1, 'Activar modo depuración', '2025-05-23 14:01:42', '2025-12-03 21:16:45', 1, 1),
(2, 'salt', 'fsddsfj38343lj0', 'string', 1, 1, 'Salt para funciones criptográficas', '2025-05-23 14:01:42', '2025-10-09 22:53:30', 1, 1),
(3, 'zona_horaria', 'America/Santiago', 'string', 1, 1, 'Zona horaria del sistema', '2025-05-23 14:01:42', '2025-10-09 22:53:30', 1, 1),
(4, 'image_height', '640', 'int', 1, 1, 'Alto por defecto para imágenes', '2025-05-23 14:01:42', '2025-10-09 22:53:30', 1, 1),
(5, 'image_width', '640', 'int', 1, 1, 'Ancho por defecto para imágenes', '2025-05-23 14:01:42', '2025-10-09 22:53:30', 1, 1),
(6, 'meta_author', 'Cápsula Tech', 'string', 2, 2, 'Autor de los metadatos del sitio', '2025-05-23 14:01:42', '2025-10-09 22:53:53', 1, 1),
(7, 'meta_application_name', 'Cápsula Tech', 'string', 2, 2, 'Nombre de la aplicación en metadatos', '2025-05-23 14:01:42', '2025-10-09 22:53:53', 1, 1),
(8, 'meta_description', 'Ecolens | EcoLens: Explorando la Fauna Chilena con IA', 'string', 2, 2, 'Descripción para SEO', '2025-05-23 14:01:42', '2025-12-02 22:19:20', 1, 1),
(9, 'meta_generator', 'CMS Cápsula Tech', 'string', 2, 2, 'Generador de metadatos', '2025-05-23 14:01:42', '2025-10-09 22:53:53', 1, 1),
(10, 'meta_keywords', 'Aqui se deben incluir la descripción del sitio', 'string', 2, 2, 'Palabras clave SEO', '2025-05-23 14:01:42', '2025-10-09 22:53:53', 1, 1),
(11, 'viewport', 'Define cómo se debe ajustar y escalar el contenido...', 'string', 2, 2, 'Meta viewport', '2025-05-23 14:01:42', '2025-10-09 22:53:53', 1, 1),
(12, 'robots', 'Controla el comportamiento de los motores de búsqueda...', 'string', 2, 2, 'Meta robots', '2025-05-23 14:01:42', '2025-10-09 22:53:53', 1, 1),
(13, 'codigo_ga', 'Código de seguimiento de Google Analytics.', 'string', 2, 2, 'Código Google Analytics', '2025-05-23 14:01:42', '2025-10-09 22:53:53', 1, 1),
(14, 'site_name', 'Ecolens', 'string', 3, 3, 'Nombre público del sitio', '2025-05-23 14:01:42', '2025-10-10 10:01:25', 1, 1),
(15, 'site_domain', 'ecolens.site', 'string', 3, 3, 'Dominio principal del sitio', '2025-05-23 14:01:42', '2025-10-10 10:01:30', 1, 1),
(16, 'idioma_sitio', 'es-ES', 'string', 3, 3, 'Idioma principal del sitio', '2025-05-23 14:01:42', '2025-10-09 22:53:58', 1, 1),
(17, 'panel_admin_name', 'Panel de Administración de Cápsula Tech V5.0.0', 'string', 3, 3, 'Nombre del panel de administración', '2025-05-23 14:01:42', '2025-10-09 22:53:58', 1, 1),
(18, 'email_admin', 'contacto@sitiocliente.cl', 'string', 3, 3, 'Correo electrónico principal del administrador', '2025-05-23 14:01:42', '2025-10-09 22:53:58', 1, 1),
(19, 'sitio_online', 'yes', 'bool', 3, 3, '¿El sitio está online?', '2025-05-23 14:01:42', '2025-10-28 22:27:32', 1, 1),
(20, 'contenido_pie', 'Derechos de autor © 2025', 'string', 3, 3, 'Texto de pie de página', '2025-05-23 14:01:42', '2025-10-09 22:53:58', 1, 1),
(21, 'site_emblem', 'Consultor TI y Mentor en Innovación', 'string', 3, 3, 'Emblema o slogan del sitio', '2025-05-23 14:01:42', '2025-10-09 22:53:58', 1, 1),
(22, 'texto_login', 'Si necesitas un nuevo usuario, por favor contáctanos...', 'string', 3, 3, 'Mensaje en el login', '2025-05-23 14:01:42', '2025-10-09 22:53:58', 1, 1),
(23, 'sitio_autor', 'https://www.capsulatech.cl', 'string', 3, 3, 'URL del autor del sitio', '2025-05-23 14:01:42', '2025-10-09 22:53:58', 1, 1),
(24, 'ruta_imagenes', '/panel_admin/images', 'string', 3, 3, 'Ruta base de imágenes en el panel', '2025-05-23 14:01:42', '2025-10-09 22:53:58', 1, 1),
(25, 'cliente_nombre', 'Ecolens', 'string', 3, 3, 'Nombre del cliente para saludos y personalización', '2025-05-23 16:59:11', '2025-10-09 23:02:09', 1, 1),
(26, 'geoapify_api_key', '6dacdd4af3e2491eb482fec8db6d31fe', 'string', 1, 1, 'API Key de Geoapify para clima y geolocalización', '2025-05-23 17:43:15', '2025-10-09 22:53:30', NULL, NULL),
(27, 'latitud_fallback', '-33.45', 'float', 1, 1, 'Latitud de fallback (ej: Santiago)', '2025-05-23 17:43:16', '2025-10-09 22:53:30', NULL, NULL),
(28, 'longitud_fallback', '-70.67', 'float', 1, 1, 'Longitud de fallback (ej: Santiago)', '2025-05-23 17:43:16', '2025-10-09 22:53:30', NULL, NULL),
(29, 'sitio_layout', 'Personal', 'string', 3, 3, 'Nombre del layout activo del sitio', '2025-05-23 20:02:24', '2025-10-09 22:53:58', 1, 1),
(30, 'color_navbar_cms', '#1d1d1d', 'string', 4, 1, 'Color de fondo del navbar del CMS', '1900-01-01 00:00:00', '2025-06-11 17:23:23', 1, 1),
(31, 'color_links_navbar_nivel_1', '#ffffff', 'string', 4, 1, 'Color de los links Nivel_1 Navbar del CMS', '1900-01-01 00:00:00', '2025-05-28 16:01:38', 1, 1),
(32, 'color_links_navbar_nivel_2', '#ffffff', 'string', 4, 1, 'Corresponde al color de los links del menú del CMS', '1900-01-01 00:00:00', '2025-05-28 16:01:40', 1, 1),
(69, 'canonical_url', 'no', 'string', 2, 2, 'URL canónica de la página', '2025-05-30 23:02:17', '2025-10-09 22:53:53', 1, 1),
(70, 'og_site_name', 'no', 'string', 2, 2, 'Nombre del sitio para Open Graph', '2025-05-30 23:02:17', '2025-10-09 22:53:53', 1, 1),
(71, 'og_title', 'no', 'string', 2, 2, 'Título de la página para Open Graph', '2025-05-30 23:02:17', '2025-10-09 22:53:53', 1, 1),
(72, 'og_description', 'no', 'string', 2, 2, 'Descripción de la página para Open Graph', '2025-05-30 23:02:17', '2025-10-09 22:53:53', 1, 1),
(73, 'og_url', 'no', 'string', 2, 2, 'URL de la página para Open Graph', '2025-05-30 23:02:17', '2025-10-09 22:53:53', 1, 1),
(74, 'og_type', 'website', 'string', 2, 2, 'Tipo de contenido Open Graph (p.ej. website, article)', '2025-05-30 23:02:17', '2025-10-09 22:53:53', 1, 1),
(75, 'og_image', 'no', 'string', 2, 2, 'URL de la imagen para Open Graph', '2025-05-30 23:02:17', '2025-10-09 22:53:53', 1, 1),
(76, 'twitter_card', 'summary_large_image', 'string', 2, 2, 'Tipo de tarjeta de Twitter (summary, summary_large_image)', '2025-05-30 23:02:17', '2025-10-09 22:53:53', 1, 1),
(77, 'twitter_site', 'no', 'string', 2, 2, 'Cuenta de Twitter del sitio (p.ej. @MiSitio)', '2025-05-30 23:02:17', '2025-10-09 22:53:53', 1, 1),
(78, 'twitter_title', 'no', 'string', 2, 2, 'Título para Twitter Card', '2025-05-30 23:02:17', '2025-10-09 22:53:53', 1, 1),
(79, 'twitter_description', 'no', 'string', 2, 2, 'Descripción para Twitter Card', '2025-05-30 23:02:17', '2025-10-09 22:53:53', 1, 1),
(80, 'twitter_image', 'no', 'string', 2, 2, 'URL de la imagen para Twitter Card', '2025-05-30 23:02:17', '2025-10-09 22:53:53', 1, 1),
(81, 'theme_color', '#000000', 'color', 2, 2, 'Color principal del tema (meta theme-color)', '2025-05-30 23:02:17', '2025-10-09 22:53:53', 1, 1),
(82, 'apple_mobile_web_app_capable', 'yes', 'bool', 2, 2, 'Permitir modo web-app en iOS (apple-mobile-web-app-capable)', '2025-05-30 23:02:17', '2025-10-09 22:53:53', 1, 1),
(83, 'apple_mobile_web_app_title', 'no', 'string', 2, 2, 'Título en pantalla completa en iOS (apple-mobile-web-app-title)', '2025-05-30 23:02:17', '2025-10-09 22:53:53', 1, 1),
(84, 'apple_mobile_web_app_status_bar_style', 'default', 'string', 2, 2, 'Estilo de status bar en iOS (apple-mobile-web-app-status-bar-style)', '2025-05-30 23:02:17', '2025-10-09 22:53:53', 1, 1),
(85, 'manifest_url', '/manifest.json', 'string', 2, 2, 'Ruta al manifiesto PWA (manifest.json)', '2025-05-30 23:02:17', '2025-10-09 22:53:53', 1, 1),
(86, 'json_ld', 'no', 'json', 2, 2, 'JSON-LD de esquema (Schema.org) para organización', '2025-05-30 23:02:17', '2025-10-09 22:53:53', 1, 1),
(87, 'api_secret_token', 'ABCabc123', 'string', 1, 1, 'Token secreto para leer desde la API', '2025-06-03 13:13:21', '2025-10-09 22:53:30', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paginas`
--

CREATE TABLE `paginas` (
  `pag_id` int(10) UNSIGNED NOT NULL COMMENT 'ID único de la página',
  `pag_titulo` varchar(255) NOT NULL COMMENT 'Título de la página (único)',
  `pag_contenido_antes` longtext DEFAULT NULL COMMENT 'Contenido HTML de la página',
  `pag_contenido_despues` longtext DEFAULT NULL COMMENT 'Contenido HTML después de la página ',
  `pag_fuente_contenido` enum('usar_plantilla','editar_directo') DEFAULT NULL COMMENT 'Indica si se carga desde plantilla o se edita código directo',
  `pag_plantilla` varchar(255) DEFAULT NULL COMMENT 'Nombre de archivo de plantilla en Views (solo nombre, con .php)',
  `pag_contenido_programador` longtext DEFAULT NULL COMMENT 'Bloque de código editable por un programador',
  `pag_css_programador` text DEFAULT NULL COMMENT 'CSS exclusivo para esta página',
  `pag_slug` varchar(255) DEFAULT NULL COMMENT 'Slug de la página (único)',
  `pag_estado` enum('borrador','publicado') NOT NULL DEFAULT 'borrador' COMMENT 'Estado de la página',
  `pag_acceso` enum('publica','privada') DEFAULT 'publica' COMMENT 'Define si la página es de acceso público (visible para todos) o privado (solo usuarios autenticados)',
  `pag_autor_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del autor de la página',
  `pag_mostrar_menu` enum('SI','NO') DEFAULT NULL COMMENT '¿Enlace en menú principal?',
  `pag_posicion` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Posición de la página en el menú',
  `pag_label` varchar(100) DEFAULT NULL COMMENT 'Label del hipervínculo de la Página',
  `pag_modo_contenido` varchar(100) DEFAULT 'autoadministrable' COMMENT 'Modo de edición de la Página',
  `pag_icono` varchar(100) DEFAULT NULL COMMENT 'Ícono de la Página',
  `pag_mostrar_menu_secundario` enum('SI','NO') DEFAULT NULL COMMENT '¿Enlace en menú secundario?',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabla para almacenar las páginas del sitio web.';

--
-- Volcado de datos para la tabla `paginas`
--

INSERT INTO `paginas` (`pag_id`, `pag_titulo`, `pag_contenido_antes`, `pag_contenido_despues`, `pag_fuente_contenido`, `pag_plantilla`, `pag_contenido_programador`, `pag_css_programador`, `pag_slug`, `pag_estado`, `pag_acceso`, `pag_autor_id`, `pag_mostrar_menu`, `pag_posicion`, `pag_label`, `pag_modo_contenido`, `pag_icono`, `pag_mostrar_menu_secundario`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'Inicio', '', '', 'usar_plantilla', 'index.php', '', '', 'inicio', 'publicado', 'publica', 1, 'SI', 1, 'Inicio', 'administrado_programador', 'bi-house|#000000', 'NO', '1900-01-01 00:00:00', '2025-10-12 12:03:43', 1, 1),
(2, 'Nosotros', '', '', 'usar_plantilla', 'nosotros.php', '', '', 'nosotros', 'publicado', 'publica', 1, 'SI', 2, 'Nosotros', 'administrado_programador', 'bi-person|#000000', 'NO', '1900-01-01 00:00:00', '2025-10-28 15:43:50', 1, 1),
(3, 'Servicios', '<h2>Servicios</h2>', '', 'usar_plantilla', 'view_servicios.php', '', '', 'servicios', 'borrador', 'publica', 1, 'SI', 10, 'Servicios', 'administrado_programador', 'bi-gear|#000000', 'NO', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(4, 'Portafolios', '<h2>Mis Proyectos</h2>', '', 'usar_plantilla', 'view_proyectos.php', '', '', 'portafolios', 'borrador', 'publica', 1, 'SI', 11, 'Portafolios', 'administrado_programador', 'bi-kanban|#000000', 'NO', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(5, 'Experiencia', '<h2>Mi Trayectoria</h2>', '', 'usar_plantilla', 'view_experiencias.php', '', '', 'experiencia', 'borrador', 'publica', 1, 'SI', 12, 'Experiencia', 'administrado_programador', 'bi-briefcase|#000000', 'NO', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(6, 'Artículos', '<h2>Blog & Artículos</h2>', '', 'usar_plantilla', 'view_articulos.php', NULL, NULL, 'articulos', 'borrador', 'publica', 1, 'SI', 13, 'Artículos', 'autoadministrable', 'bi-newspaper', 'NO', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(7, 'Testimonios', '<h2>Lo que dicen de m&iacute;</h2>', '', 'usar_plantilla', 'view_testimonios.php', '', '', 'testimonios', 'borrador', 'publica', 1, 'SI', 14, 'Testimonios', 'administrado_programador', 'bi-chat-quote|#000000', 'NO', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(8, 'Contacto', '', '', 'usar_plantilla', 'contacto.php', '', '', 'contacto', 'publicado', 'publica', 1, 'NO', 5, 'Contacto', 'administrado_programador', 'bi-envelope|#000000', 'SI', '1900-01-01 00:00:00', '2025-12-03 10:41:21', 1, 1),
(9, 'offline', '', '', 'editar_directo', '', '<!DOCTYPE html>\r\n<html lang=\"es\">\r\n  <head>\r\n    <meta charset=\"UTF-8\" />\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\" />\r\n    <title>EcoLens | Sitio en Mantenimiento</title>\r\n    <link\r\n      href=\"https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;700&family=Lora:wght@700&display=swap\"\r\n      rel=\"stylesheet\"\r\n    />\r\n    <link rel=\"icon\" type=\"image/x-icon\" href=\"recursos/uploads/imagenes/favicon.ico\" />\r\n  </head>\r\n\r\n  <body>\r\n    <div class=\"offline-card\">\r\n      <img src=\"recursos/uploads/imagenes/logo-ecolens.png\" alt=\"EcoLens Logo\" />\r\n      <h1>Estamos en mantenimiento</h1>\r\n      <p>\r\n        El sitio de <strong>EcoLens</strong> se encuentra temporalmente\r\n        fuera de línea mientras realizamos mejoras y actualizaciones.\r\n      </p>\r\n      <p class=\"hint\">\r\n        No te preocupes — volveremos pronto con nuevas funciones y\r\n        mejor rendimiento.\r\n      </p>\r\n      <a href=\"#\" class=\"cta-button\" onclick=\"window.location.reload()\">Reintentar</a>\r\n    </div>\r\n\r\n    <footer>© 2025 EcoLens · Cápsula Tech</footer>\r\n  </body>\r\n</html>', ':root {\r\n        --primary-color: #45ad82;\r\n        --secondary-color: #1f3b3a;\r\n        --light-bg: #f7f8f9;\r\n      }\r\n\r\n      * {\r\n        box-sizing: border-box;\r\n        margin: 0;\r\n        padding: 0;\r\n      }\r\n\r\n      body {\r\n        font-family: \"Nunito Sans\", sans-serif;\r\n        background-color: var(--secondary-color);\r\n        color: #fff;\r\n        display: flex;\r\n        flex-direction: column;\r\n        align-items: center;\r\n        justify-content: center;\r\n        height: 100vh;\r\n        text-align: center;\r\n        padding: 2rem;\r\n      }\r\n\r\n      .offline-card {\r\n        background-color: #fff;\r\n        color: var(--secondary-color);\r\n        padding: 2.5rem 3rem;\r\n        border-radius: 16px;\r\n        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);\r\n        max-width: 600px;\r\n      }\r\n\r\n      .offline-card img {\r\n        width: 90px;\r\n        height: auto;\r\n        margin-bottom: 1rem;\r\n      }\r\n\r\n      h1 {\r\n        font-family: \"Lora\", serif;\r\n        color: var(--secondary-color);\r\n        font-size: 2rem;\r\n        margin-bottom: 1rem;\r\n      }\r\n\r\n      p {\r\n        font-size: 1.1rem;\r\n        line-height: 1.6;\r\n        color: #333;\r\n      }\r\n\r\n      .hint {\r\n        margin-top: 1.5rem;\r\n        color: #666;\r\n        font-size: 0.95rem;\r\n      }\r\n\r\n      .cta-button {\r\n        display: inline-block;\r\n        margin-top: 2rem;\r\n        padding: 0.8rem 2rem;\r\n        background-color: var(--primary-color);\r\n        color: #fff;\r\n        border-radius: 40px;\r\n        text-decoration: none;\r\n        font-weight: 700;\r\n        transition: background 0.3s ease, transform 0.2s ease;\r\n      }\r\n\r\n      .cta-button:hover {\r\n        background-color: #3c9771;\r\n        transform: translateY(-2px);\r\n      }\r\n\r\n      footer {\r\n        margin-top: 3rem;\r\n        font-size: 0.9rem;\r\n        opacity: 0.8;\r\n      }\r\n\r\n      @media (max-width: 600px) {\r\n        .offline-card {\r\n          padding: 2rem 1.5rem;\r\n        }\r\n        h1 {\r\n          font-size: 1.6rem;\r\n        }\r\n      }', 'offline', 'publicado', 'publica', 1, 'NO', 9, '', 'administrado_programador', 'bi-house|#000000', 'NO', '1900-01-01 00:00:00', '2025-10-12 17:12:00', 1, 1),
(10, 'Blog', '', '', 'editar_directo', '', '  <div class=\"doc-container\">\r\n    <a id=\"top\"></a>\r\n\r\n    <!-- Menú de navegación -->\r\n    <nav class=\"doc-toc\">\r\n      <h2 class=\"doc-toc__title\">Índice de contenidos</h2>\r\n      <ul class=\"doc-toc__list\">\r\n        <li class=\"doc-toc__item\"><a class=\"doc-toc__link\" href=\"#descripcion\">1. Descripción general</a></li>\r\n        <li class=\"doc-toc__item\"><a class=\"doc-toc__link\" href=\"#arquitectura\">2. Arquitectura del sistema</a></li>\r\n        <li class=\"doc-toc__item\"><a class=\"doc-toc__link\" href=\"#pila\">3. Pila de tecnología</a></li>\r\n        <li class=\"doc-toc__item\"><a class=\"doc-toc__link\" href=\"#caracteristicas\">4. Características clave</a></li>\r\n        <li class=\"doc-toc__item\"><a class=\"doc-toc__link\" href=\"#flujo-contenido\">5. Flujo de contenido</a></li>\r\n        <li class=\"doc-toc__item\"><a class=\"doc-toc__link\" href=\"#puntos-entrada\">6. Puntos de entrada</a></li>\r\n        <li class=\"doc-toc__item\"><a class=\"doc-toc__link\" href=\"#instalacion\">7. Instalación y configuración</a></li>\r\n        <li class=\"doc-toc__item\"><a class=\"doc-toc__link\" href=\"#integracion\">8. Integración y extensibilidad</a></li>\r\n        <li class=\"doc-toc__item\"><a class=\"doc-toc__link\" href=\"#herramientas\">9. Herramientas de desarrollo</a></li>\r\n      </ul>\r\n    </nav>\r\n\r\n    <!-- Sección 1 -->\r\n    <section id=\"descripcion\" class=\"doc-section\">\r\n      <h1 class=\"doc-section__heading\">1. Descripción general de CMS V4</h1>\r\n      <p class=\"doc-section__text\">\r\n        Este documento proporciona una visión completa de CMS V4, un sistema de gestión de contenido\r\n        modular construido sobre el marco Yii2. Cubre la arquitectura del sistema, las características\r\n        clave, la pila de tecnología y los componentes principales para ayudar a los desarrolladores y\r\n        administradores de sistemas a comprender la estructura y las capacidades generales. Para ver los\r\n        procedimientos de instalación detallados, consulte <em>Sistema de instalación</em>. Para obtener\r\n        información específica sobre la administración del backend, consulte <em>Panel de administración</em>.\r\n        Para obtener instrucciones de desarrollo e información sobre extensiones, consulte\r\n        <em>Guía de desarrollo</em>. [oai_citation:0‡RmunozMM_CMS_V4 _ Wiki Profundo.pdf]\r\n      </p>\r\n      <p class=\"doc-section__back\"><a class=\"doc-back-link\" href=\"#top\">↑ Volver arriba</a></p>\r\n    </section>\r\n\r\n    <!-- Sección 2 -->\r\n    <section id=\"arquitectura\" class=\"doc-section\">\r\n      <h1 class=\"doc-section__heading\">2. Arquitectura del sistema</h1>\r\n      <p class=\"doc-section__text\">\r\n        CMS V4 implementa una arquitectura modular con una clara separación entre la entrega de frontend,\r\n        la administración de backend, los flujos de trabajo de instalación y la integración de API externas.\r\n        [oai_citation:1‡RmunozMM_CMS_V4 _ Wiki Profundo.pdf]\r\n      </p>\r\n      <h2 class=\"doc-section__subheading\">2.1 Componentes principales del sistema</h2>\r\n      <ul class=\"doc-section__list\">\r\n        <li>Estructura modular (módulos, widgets, helpers, controladores)</li>\r\n        <li>Gestión de contenidos (Artículos, Servicios, Clientes, Páginas dinámicas)</li>\r\n        <li>Galería multimedia (TinyMCE + directorio <code>recursos/</code>)</li>\r\n        <li>Sistema de auditoría (behaviors y triggers)</li>\r\n        <li>Sistema de widgets reutilizables</li>\r\n        <li>Integración de API REST (<code>web-api.php</code>)</li>\r\n      </ul>\r\n      <p class=\"doc-section__back\"><a class=\"doc-back-link\" href=\"#top\">↑ Volver arriba</a></p>\r\n    </section>\r\n\r\n    <!-- Sección 3 -->\r\n    <section id=\"pila\" class=\"doc-section\">\r\n      <h1 class=\"doc-section__heading\">3. Pila de tecnología</h1>\r\n      <p class=\"doc-section__text\">\r\n        La pila tecnológica de CMS V4 está basada en PHP 8.x y el framework Yii2, apoyada por Composer\r\n        para gestión de dependencias y ActiveRecord para el acceso a datos. En el frontend utiliza HTML5,\r\n        CSS3 (Bootstrap), jQuery y el editor TinyMCE, y ofrece consumo headless a través de aplicaciones\r\n        Vue.js y móviles. [oai_citation:2‡RmunozMM_CMS_V4 _ Wiki Profundo.pdf]\r\n      </p>\r\n      <p class=\"doc-section__back\"><a class=\"doc-back-link\" href=\"#top\">↑ Volver arriba</a></p>\r\n    </section>\r\n\r\n    <!-- Sección 4 -->\r\n    <section id=\"caracteristicas\" class=\"doc-section\">\r\n      <h1 class=\"doc-section__heading\">4. Características y capacidades clave</h1>\r\n      <table class=\"doc-section__table\">\r\n        <thead>\r\n          <tr>\r\n            <th>Feature</th>\r\n            <th>Implementación</th>\r\n            <th>Ruta / Directorio</th>\r\n          </tr>\r\n        </thead>\r\n        <tbody>\r\n          <tr>\r\n            <td>Estructura modular</td>\r\n            <td>Separación por módulos, widgets, ayudantes, controladores</td>\r\n            <td><code>panel-admin/</code></td>\r\n          </tr>\r\n          <tr>\r\n            <td>Gestión de contenidos</td>\r\n            <td>Artículos, servicios, clientes, páginas dinámicas</td>\r\n            <td><code>panel-admin/models/</code></td>\r\n          </tr>\r\n          <tr>\r\n            <td>Galería multimedia</td>\r\n            <td>Gestión de imágenes y archivos con TinyMCE</td>\r\n            <td><code>recursos/</code></td>\r\n          </tr>\r\n          <tr>\r\n            <td>Sistema de auditoría</td>\r\n            <td>Seguimiento estandarizado via triggers y behaviors</td>\r\n            <td>Todos los modelos</td>\r\n          </tr>\r\n          <tr>\r\n            <td>Sistema de widgets</td>\r\n            <td>Componentes de interfaz reutilizables</td>\r\n            <td><code>app/helpers/</code>, <code>app/widgets/</code></td>\r\n          </tr>\r\n          <tr>\r\n            <td>Integración de API</td>\r\n            <td>Puntos de conexión REST para consumo externo</td>\r\n            <td><code>web-api.php</code>, <code>panel-admin/api/</code></td>\r\n          </tr>\r\n        </tbody>\r\n      </table>\r\n      <p class=\"doc-section__back\"><a class=\"doc-back-link\" href=\"#top\">↑ Volver arriba</a></p>\r\n    </section>\r\n\r\n    <!-- Sección 5 -->\r\n    <section id=\"flujo-contenido\" class=\"doc-section\">\r\n      <h1 class=\"doc-section__heading\">5. Flujo de contenido</h1>\r\n      <ul class=\"doc-section__list\">\r\n        <li>Creación (TinyMCE → validación de formulario → ActiveRecord)</li>\r\n        <li>Procesamiento (eventos, behaviors)</li>\r\n        <li>Almacenamiento (filesystem + MySQL)</li>\r\n        <li>Renderizado (HTML frontend o JSON API)</li>\r\n      </ul>\r\n      <p class=\"doc-section__back\"><a class=\"doc-back-link\" href=\"#top\">↑ Volver arriba</a></p>\r\n    </section>\r\n\r\n    <!-- Sección 6 -->\r\n    <section id=\"puntos-entrada\" class=\"doc-section\">\r\n      <h1 class=\"doc-section__heading\">6. Puntos de entrada</h1>\r\n      <ul class=\"doc-section__list\">\r\n        <li><code>index.php</code> (sitio público)</li>\r\n        <li><code>panel-admin/web/index.php</code> (backend)</li>\r\n        <li><code>web-api.php</code> (API REST)</li>\r\n        <li>Comandos CLI (<code>yii &lt;comando&gt;</code>)</li>\r\n      </ul>\r\n      <p class=\"doc-section__back\"><a class=\"doc-back-link\" href=\"#top\">↑ Volver arriba</a></p>\r\n    </section>\r\n\r\n    <!-- Sección 7 -->\r\n    <section id=\"instalacion\" class=\"doc-section\">\r\n      <h1 class=\"doc-section__heading\">7. Instalación y configuración</h1>\r\n      <ol class=\"doc-section__list\">\r\n        <li>Directorio <code>install/</code> y verificación de extensiones</li>\r\n        <li>Configuración de BD y credenciales</li>\r\n        <li>Creación de usuario administrador</li>\r\n        <li>Finalización y test de acceso</li>\r\n      </ol>\r\n      <p class=\"doc-section__back\"><a class=\"doc-back-link\" href=\"#top\">↑ Volver arriba</a></p>\r\n    </section>\r\n\r\n    <!-- Sección 8 -->\r\n    <section id=\"integracion\" class=\"doc-section\">\r\n      <h1 class=\"doc-section__heading\">8. Integración y extensibilidad</h1>\r\n      <h2 class=\"doc-section__subheading\">8.1 API y arquitectura de integraciones externas</h2>\r\n      <p class=\"doc-section__text\">\r\n        CMS V4 soporta arquitecturas modernas exponiendo endpoints REST con autenticación y respuestas JSON,\r\n        consumibles desde Vue.js, aplicaciones móviles o terceros. [oai_citation:3‡RmunozMM_CMS_V4 _ Wiki Profundo.pdf]\r\n      </p>\r\n      <p class=\"doc-section__back\"><a class=\"doc-back-link\" href=\"#top\">↑ Volver arriba</a></p>\r\n    </section>\r\n\r\n    <!-- Sección 9 -->\r\n    <section id=\"herramientas\" class=\"doc-section\">\r\n      <h1 class=\"doc-section__heading\">9. Herramientas de desarrollo</h1>\r\n      <ul class=\"doc-section__list\">\r\n        <li>Consola Yii (<code>yii migrate</code>, <code>yii gii</code>)</li>\r\n        <li>Composer (<code>composer install</code>, <code>composer update</code>)</li>\r\n        <li>Scripts de migración y seeders</li>\r\n        <li>GitHub Actions para CI/CD de tests y despliegue de docs</li>\r\n      </ul>\r\n      <p class=\"doc-section__back\"><a class=\"doc-back-link\" href=\"#top\">↑ Volver arriba</a></p>\r\n    </section>\r\n\r\n  </div>\r\n', '/* Contenedor principal */\r\n.doc-container {\r\n  max-width: 800px;\r\n  margin: 0 auto;\r\n  padding: 1rem;\r\n  font-family: Arial, sans-serif;\r\n  line-height: 1.6;\r\n  color: #333;\r\n  background: #fff;\r\n}\r\n\r\n/* Menú de contenidos (TOC) */\r\n.doc-toc {\r\n  margin-bottom: 1.5rem;\r\n  border: 1px solid #ddd;\r\n  padding: 1rem;\r\n  border-radius: 4px;\r\n  background: #fafafa;\r\n}\r\n.doc-toc__title {\r\n  margin-bottom: 0.75rem;\r\n  color: #2A3F54;\r\n  font-size: 1.25rem;\r\n}\r\n.doc-toc__list {\r\n  list-style: none;\r\n  padding: 0;\r\n  margin: 0;\r\n}\r\n.doc-toc__item {\r\n  margin: 0.25rem 0;\r\n}\r\n.doc-toc__link {\r\n  color: #4596E6;\r\n  text-decoration: none;\r\n  font-weight: 500;\r\n}\r\n.doc-toc__link:hover {\r\n  text-decoration: underline;\r\n}\r\n\r\n/* Secciones de contenido */\r\n.doc-section {\r\n  margin-bottom: 2rem;\r\n}\r\n.doc-section__heading {\r\n  color: #2A3F54;\r\n  margin-top: 1.5rem;\r\n  margin-bottom: 0.5rem;\r\n  font-size: 1.5rem;\r\n}\r\n.doc-section__subheading {\r\n  color: #2A3F54;\r\n  margin-top: 1rem;\r\n  margin-bottom: 0.5rem;\r\n  font-size: 1.25rem;\r\n}\r\n.doc-section__text {\r\n  margin-bottom: 1rem;\r\n}\r\n.doc-section__list {\r\n  margin: 0.5rem 0 1rem 1.5rem;\r\n}\r\n.doc-section__list li {\r\n  margin-bottom: 0.25rem;\r\n}\r\n\r\n/* Tablas */\r\n.doc-section table {\r\n  width: 100%;\r\n  border-collapse: collapse;\r\n  margin: 1rem 0;\r\n}\r\n.doc-section th,\r\n.doc-section td {\r\n  border: 1px solid #ccc;\r\n  padding: 0.5rem;\r\n  text-align: left;\r\n}\r\n.doc-section th {\r\n  background: #f0f0f0;\r\n}\r\n\r\n/* Código inline */\r\n.doc-section code {\r\n  background: #f4f4f4;\r\n  padding: 2px 4px;\r\n  font-family: Consolas, monospace;\r\n  border-radius: 4px;\r\n}\r\n\r\n/* Enlace “volver arriba” */\r\n.doc-section__back {\r\n  text-align: right;\r\n  margin-top: 1rem;\r\n}\r\n.doc-back-link {\r\n  color: #4596E6;\r\n  font-size: 0.9rem;\r\n  text-decoration: none;\r\n}\r\n.doc-back-link:hover {\r\n  text-decoration: underline;\r\n}', 'blog', 'borrador', 'publica', 1, 'NO', 7, 'Blog y Noticias', 'administrado_programador', 'bi-house|#000000', 'SI', '1900-01-01 00:00:00', '2025-10-12 15:14:52', 1, 1),
(11, 'Términos y condiciones', '', '', 'editar_directo', '', '<div class=\"tc-page\">\r\n  <div class=\"tc-header\">\r\n    <h1>Términos y Condiciones de Uso – EcoLens</h1>\r\n    <p class=\"tc-subtitle\">\r\n      Esta página describe las reglas de uso de la plataforma, el tratamiento de datos y el alcance del proyecto EcoLens.\r\n    </p>\r\n    <p class=\"tc-updated\">\r\n      Última actualización: <strong>02 de diciembre 2025</strong>\r\n    </p>\r\n  </div>\r\n\r\n  <div class=\"tc-cards-grid\">\r\n\r\n    <!-- 1. Propósito del proyecto -->\r\n    <section class=\"tc-card\">\r\n      <div class=\"tc-badge\">1</div>\r\n      <h2 class=\"tc-card-title\">Propósito del proyecto</h2>\r\n      <p>\r\n        <strong>EcoLens</strong> es una plataforma tecnológica diseñada para la identificación y\r\n        clasificación de fauna silvestre mediante modelos de Inteligencia Artificial entrenados en\r\n        especies nativas de Chile.\r\n      </p>\r\n      <p>\r\n        Su misión es apoyar la investigación, la educación y la conservación de la biodiversidad,\r\n        promoviendo el uso responsable de la tecnología aplicada al medioambiente.\r\n      </p>\r\n    </section>\r\n\r\n    <!-- 2. Alcance y uso del sitio -->\r\n    <section class=\"tc-card\">\r\n      <div class=\"tc-badge\">2</div>\r\n      <h2 class=\"tc-card-title\">Alcance y uso del sitio</h2>\r\n      <p>\r\n        El uso del sitio <code>ecolens.site</code> y sus servicios asociados implica la aceptación\r\n        de estos Términos y Condiciones. EcoLens está orientado a fines educativos, científicos y de\r\n        divulgación.\r\n      </p>\r\n      <p>\r\n        No constituye un servicio comercial ni reemplaza el trabajo de profesionales en biología\r\n        o gestión ambiental.\r\n      </p>\r\n      <ul>\r\n        <li>El usuario se compromete a utilizar la plataforma con fines legítimos y éticos.</li>\r\n        <li>Queda prohibido subir imágenes que vulneren derechos de autor, privacidad o bienestar animal.</li>\r\n        <li>EcoLens podrá limitar el acceso en caso de uso indebido o comportamiento abusivo.</li>\r\n      </ul>\r\n    </section>\r\n\r\n    <!-- 3. Propiedad intelectual -->\r\n    <section class=\"tc-card\">\r\n      <div class=\"tc-badge\">3</div>\r\n      <h2 class=\"tc-card-title\">Propiedad intelectual</h2>\r\n      <p>\r\n        Todo el contenido, código fuente, modelos de IA, diseño y documentación de EcoLens pertenecen\r\n        a su autor y colaboradores. El uso o redistribución de estos recursos, totales o parciales,\r\n        requiere autorización previa por escrito.\r\n      </p>\r\n      <p>\r\n        Las imágenes y datos aportados por los usuarios permanecen bajo su propiedad, pero al\r\n        enviarlos otorgan una licencia no exclusiva para su análisis, uso interno del sistema y\r\n        eventual entrenamiento de los modelos asociados al proyecto.\r\n      </p>\r\n    </section>\r\n\r\n    <!-- 4. Privacidad y tratamiento de datos -->\r\n    <section class=\"tc-card\">\r\n      <div class=\"tc-badge\">4</div>\r\n      <h2 class=\"tc-card-title\">Privacidad y tratamiento de datos</h2>\r\n      <p>\r\n        EcoLens respeta la privacidad de sus usuarios. Los datos personales y los metadatos asociados\r\n        a las imágenes (por ejemplo, fecha, especie o ubicación general) se almacenan únicamente para\r\n        fines de investigación y mejora de los modelos.\r\n      </p>\r\n      <p>\r\n        No se comparten con terceros ni se publican sin consentimiento explícito. Las personas usuarias\r\n        pueden solicitar la eliminación de sus datos escribiendo a\r\n        <a href=\"mailto:contacto@ecolens.site\">contacto@ecolens.site</a>.\r\n      </p>\r\n    </section>\r\n\r\n    <!-- 5. Limitación de responsabilidad -->\r\n    <section class=\"tc-card\">\r\n      <div class=\"tc-badge\">5</div>\r\n      <h2 class=\"tc-card-title\">Limitación de responsabilidad</h2>\r\n      <p>\r\n        Los resultados de clasificación entregados por EcoLens son aproximaciones generadas por\r\n        algoritmos de aprendizaje automático. La plataforma no garantiza la identificación exacta\r\n        de especies ni se responsabiliza por el uso que se haga de los resultados.\r\n      </p>\r\n      <p>\r\n        EcoLens es una herramienta de apoyo y no sustituye la validación científica por especialistas\r\n        en fauna o biodiversidad.\r\n      </p>\r\n    </section>\r\n\r\n    <!-- 6. Colaboraciones y uso institucional -->\r\n    <section class=\"tc-card\">\r\n      <div class=\"tc-badge\">6</div>\r\n      <h2 class=\"tc-card-title\">Colaboraciones y uso institucional</h2>\r\n      <p>\r\n        Universidades, fundaciones u otras instituciones que deseen integrar EcoLens en proyectos de\r\n        investigación o divulgación deberán solicitar autorización previa.\r\n      </p>\r\n      <p>\r\n        Toda colaboración oficial será reconocida públicamente en los canales del proyecto, promoviendo\r\n        la ciencia abierta y la transparencia.\r\n      </p>\r\n    </section>\r\n\r\n    <!-- 7. Actualizaciones de los términos -->\r\n    <section class=\"tc-card\">\r\n      <div class=\"tc-badge\">7</div>\r\n      <h2 class=\"tc-card-title\">Actualizaciones de los términos</h2>\r\n      <p>\r\n        EcoLens puede modificar estos Términos y Condiciones cuando lo estime necesario. Las versiones\r\n        actualizadas se publicarán en este mismo sitio, indicando la fecha de su última revisión.\r\n      </p>\r\n    </section>\r\n\r\n    <!-- 8. Vigencia -->\r\n    <section class=\"tc-card\">\r\n      <div class=\"tc-badge\">8</div>\r\n      <h2 class=\"tc-card-title\">Vigencia</h2>\r\n      <p>\r\n        Estos términos rigen desde su publicación en el sitio y son aplicables a todas las interacciones,\r\n        contribuciones y usos de la plataforma realizados a partir del\r\n        <strong>1 de noviembre de 2025</strong>.\r\n      </p>\r\n    </section>\r\n\r\n    <!-- 9. Compromiso ético y ambiental (destacada) -->\r\n    <section class=\"tc-card tc-card-highlight\">\r\n      <div class=\"tc-badge\">🌱</div>\r\n      <h2 class=\"tc-card-title\">Compromiso ético y ambiental</h2>\r\n      <p>\r\n        EcoLens promueve el desarrollo de inteligencia artificial al servicio de la naturaleza. Todo aporte\r\n        de datos, imágenes o conocimiento implica un compromiso con la investigación responsable, la\r\n        conservación y la difusión del valor ecológico de Chile y Latinoamérica.\r\n      </p>\r\n      <p class=\"tc-note\">\r\n        Al utilizar la plataforma, te sumas a una comunidad que busca observar, registrar y proteger la\r\n        biodiversidad, no solo consumir tecnología.\r\n      </p>\r\n    </section>\r\n\r\n  </div>\r\n</div>\r\n\r\n<style>\r\n/* Contenedor general de la página de T&C */\r\n.tc-page {\r\n  max-width: 1100px;\r\n  margin: 2.5rem auto 3.5rem;\r\n  padding: 0 1.5rem;\r\n  font-family: \'Nunito Sans\', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;\r\n}\r\n\r\n/* Cabecera de la sección */\r\n.tc-header {\r\n  text-align: center;\r\n  margin-bottom: 2.5rem;\r\n}\r\n\r\n.tc-header h1 {\r\n  font-size: 2.3rem;\r\n  margin-bottom: 0.4rem;\r\n  color: #102a43;\r\n}\r\n\r\n.tc-subtitle {\r\n  color: #64748b;\r\n  font-size: 0.98rem;\r\n  margin-bottom: 0.5rem;\r\n}\r\n\r\n.tc-updated {\r\n  font-size: 0.9rem;\r\n  color: #94a3b8;\r\n}\r\n\r\n/* Grid de tarjetas */\r\n.tc-cards-grid {\r\n  display: grid;\r\n  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));\r\n  gap: 1.5rem;\r\n}\r\n\r\n/* Tarjetas base */\r\n.tc-card {\r\n  background: #ffffff;\r\n  border-radius: 16px;\r\n  border: 1px solid #e2e8f0;\r\n  box-shadow: 0 10px 25px rgba(15, 23, 42, 0.05);\r\n  padding: 1.75rem 1.5rem 1.6rem;\r\n  position: relative;\r\n  overflow: hidden;\r\n  transition:\r\n    transform 0.18s ease,\r\n    box-shadow 0.18s ease,\r\n    border-color 0.18s ease,\r\n    background-color 0.18s ease;\r\n}\r\n\r\n.tc-card:hover {\r\n  transform: translateY(-4px);\r\n  box-shadow: 0 14px 30px rgba(15, 23, 42, 0.12);\r\n  border-color: #16a34a;\r\n}\r\n\r\n/* Badge circular con número / icono */\r\n.tc-badge {\r\n  width: 32px;\r\n  height: 32px;\r\n  border-radius: 999px;\r\n  background: #0f766e;\r\n  color: #ffffff;\r\n  display: flex;\r\n  align-items: center;\r\n  justify-content: center;\r\n  font-weight: 700;\r\n  font-size: 0.95rem;\r\n  position: absolute;\r\n  top: 1.1rem;\r\n  left: 1.25rem;\r\n}\r\n\r\n/* Títulos de cada tarjeta */\r\n.tc-card-title {\r\n  margin: 0 0 0.9rem 2.8rem;\r\n  font-size: 1.15rem;\r\n  color: #111827;\r\n}\r\n\r\n/* Texto interno */\r\n.tc-card p {\r\n  font-size: 0.95rem;\r\n  color: #374151;\r\n  margin: 0 0 0.7rem;\r\n}\r\n\r\n.tc-card ul {\r\n  margin: 0.3rem 0 0 1.1rem;\r\n  padding-left: 0;\r\n  font-size: 0.92rem;\r\n  color: #4b5563;\r\n}\r\n\r\n.tc-card ul li {\r\n  margin-bottom: 0.35rem;\r\n}\r\n\r\n/* Enlaces internos */\r\n.tc-card a {\r\n  color: #0f766e;\r\n  text-decoration: none;\r\n  font-weight: 500;\r\n}\r\n\r\n.tc-card a:hover {\r\n  text-decoration: underline;\r\n}\r\n\r\n/* Tarjeta destacada final (compromiso ético) */\r\n.tc-card-highlight {\r\n  border-color: #22c55e;\r\n  background: #ffffff;              /* igual que las otras en reposo */\r\n}\r\n\r\n.tc-card-highlight .tc-badge {\r\n  background: #22c55e;\r\n}\r\n\r\n/* Hover especial de la destacada */\r\n.tc-card-highlight:hover {\r\n  background: linear-gradient(135deg, #ecfdf5, #f9fafb);\r\n}\r\n\r\n/* Nota extra dentro de la tarjeta destacada */\r\n.tc-card-highlight .tc-note {\r\n  font-size: 0.9rem;\r\n  color: #166534;\r\n  margin-top: 0.4rem;\r\n}\r\n\r\n/* Responsivo */\r\n@media (max-width: 768px) {\r\n  .tc-header h1 {\r\n    font-size: 1.9rem;\r\n  }\r\n\r\n  .tc-card-title {\r\n    margin-left: 2.4rem;\r\n    font-size: 1.05rem;\r\n  }\r\n\r\n  .tc-card {\r\n    padding: 1.6rem 1.25rem 1.4rem;\r\n  }\r\n}\r\n</style>\r\n', '', 'terminos-y-condiciones', 'publicado', 'publica', 1, 'NO', 8, 'terminos', 'administrado_programador', 'bi-house|#000000', 'SI', '1900-01-01 00:00:00', '2025-12-02 16:30:18', 1, 1),
(12, 'Detectar', '', '', 'usar_plantilla', 'detectar.php', '', '', 'detectar', 'publicado', 'privada', 1, 'SI', 3, 'Detectar', 'administrado_programador', 'bi-house|#000000', 'NO', '2025-10-12 15:21:42', '2025-10-25 22:37:25', 1, 1),
(13, 'Mis detecciones', '', '', 'usar_plantilla', 'misdetecciones.php', '', '', 'mis-detecciones', 'publicado', 'privada', 1, 'SI', 6, 'Mis detecciones', 'administrado_programador', 'bi-house|#000000', 'NO', '2025-10-12 16:17:39', '2025-10-25 21:49:56', 1, 1),
(14, 'Monitoreo', '', '', 'usar_plantilla', 'monitoreo.php', '', '', 'monitoreo', 'publicado', 'privada', 1, 'SI', 15, 'Mi actividad', 'administrado_programador', 'bi-house|#000000', 'NO', '2025-10-12 16:26:02', '2025-12-03 22:31:17', 1, 1),
(15, 'Taxonomías', '', '', 'usar_plantilla', 'taxonomias.php', '', '', 'taxonomias', 'publicado', 'publica', 1, 'SI', 4, 'Grupos Taxonómicos', 'administrado_programador', 'bi-house|#000000', 'NO', '2025-10-16 11:34:51', '2025-10-16 20:17:58', 1, 1),
(16, 'Registro', '', '', 'usar_plantilla', 'registro.php', '', '', 'registro', 'publicado', 'publica', 1, 'NO', 16, '', 'administrado_programador', 'bi-house|#000000', 'SI', '2025-10-17 14:34:22', '2025-10-17 14:34:22', 1, 1),
(17, 'login', '', '', 'usar_plantilla', 'login.php', '', '', 'login', 'publicado', 'publica', 1, 'NO', 17, '', 'administrado_programador', 'bi-house|#000000', 'NO', '2025-10-17 15:40:05', '2025-10-17 19:40:27', 1, 1),
(18, 'MI Perfil', '', '', 'usar_plantilla', 'perfil.php', '', '', 'mi-perfil', 'publicado', 'privada', 1, 'NO', 18, 'Mi Perfil', 'administrado_programador', 'bi-house|#000000', 'NO', '2025-10-17 23:11:14', '2025-10-17 23:11:37', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil`
--

CREATE TABLE `perfil` (
  `per_id` int(10) UNSIGNED NOT NULL COMMENT 'ID del Perfil',
  `per_tipo` enum('persona','empresa') NOT NULL COMMENT 'Tipo de Perfil (Persona / Empresa)',
  `per_nombre` varchar(255) NOT NULL COMMENT 'Nombre del Perfil',
  `per_fecha_nacimiento` date DEFAULT NULL COMMENT 'Fecha de Nacimiento (o Fundación)',
  `per_lugar_nacimiento_fundacion` varchar(255) DEFAULT NULL COMMENT 'Lugar de Nacimiento o Fundación',
  `per_ubicacion` varchar(255) DEFAULT NULL COMMENT 'Ubicación',
  `per_nacionalidad` varchar(255) DEFAULT NULL COMMENT 'Nacionalidad',
  `per_correo` varchar(255) DEFAULT NULL COMMENT 'Correo Electrónico',
  `per_telefono` varchar(20) DEFAULT NULL COMMENT 'Teléfono',
  `per_direccion` varchar(255) DEFAULT NULL COMMENT 'Dirección',
  `per_linkedin` varchar(255) DEFAULT NULL COMMENT 'LinkedIn',
  `per_sitio_web` varchar(255) DEFAULT NULL COMMENT 'Sitio Web',
  `per_sector` varchar(255) DEFAULT NULL COMMENT 'Sector (Giro)',
  `per_idiomas` mediumtext DEFAULT NULL COMMENT 'Idiomas',
  `singleton` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Indicador Único (Singleton)',
  `per_imagen` varchar(255) DEFAULT NULL COMMENT 'Ruta de la Imagen del Perfil',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `perfil`
--

INSERT INTO `perfil` (`per_id`, `per_tipo`, `per_nombre`, `per_fecha_nacimiento`, `per_lugar_nacimiento_fundacion`, `per_ubicacion`, `per_nacionalidad`, `per_correo`, `per_telefono`, `per_direccion`, `per_linkedin`, `per_sitio_web`, `per_sector`, `per_idiomas`, `singleton`, `per_imagen`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'empresa', 'Ecolens', NULL, '', '', 'Chile', '', '', '', '', '', '', '', 1, 'perfil/1.jpg', '1900-01-01 00:00:00', '2025-10-28 17:17:12', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos`
--

CREATE TABLE `proyectos` (
  `pro_id` int(10) UNSIGNED NOT NULL COMMENT 'Identificador único del proyecto',
  `pro_titulo` varchar(255) NOT NULL COMMENT 'Título del proyecto',
  `pro_descripcion` mediumtext DEFAULT NULL COMMENT 'Descripción detallada del proyecto',
  `pro_resumen` varchar(255) DEFAULT NULL COMMENT 'Resumen del proyecto',
  `pro_slug` varchar(255) NOT NULL COMMENT 'Slug único para la URL del proyecto',
  `pro_estado` enum('PUBLICADO','BORRADOR') DEFAULT 'BORRADOR' COMMENT 'Estado del proyecto (publicado o en borrador)',
  `pro_destacado` enum('SI','NO') DEFAULT 'NO' COMMENT 'Indica si el proyecto está destacado',
  `pro_imagen` varchar(255) DEFAULT NULL COMMENT 'URL de la imagen del proyecto',
  `pro_ser_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del tipo de servicio relacionado con el proyecto',
  `pro_url` varchar(255) DEFAULT NULL COMMENT 'URL del proyecto',
  `pro_cli_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del cliente relacionado con el proyecto',
  `pro_fecha_inicio` date DEFAULT NULL COMMENT 'Fecha de inicio del proyecto',
  `pro_fecha_fin` date DEFAULT NULL COMMENT 'Fecha de finalización del proyecto',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabla que almacena información sobre los proyectos del sitio';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recursos`
--

CREATE TABLE `recursos` (
  `rec_id` int(10) UNSIGNED NOT NULL COMMENT 'ID único del recurso',
  `rec_titulo` varchar(255) NOT NULL COMMENT 'Título del recurso',
  `rec_slug` varchar(255) NOT NULL COMMENT 'Slug del recurso',
  `rec_tipo` enum('video','documento','imagen','enlace') NOT NULL DEFAULT 'documento' COMMENT 'Tipo de recurso (video, documento, imagen, enlace)',
  `rec_url` varchar(255) NOT NULL COMMENT 'URL o ruta del recurso',
  `rec_descripcion` mediumtext DEFAULT NULL COMMENT 'Descripción del recurso',
  `rec_imagen` varchar(255) DEFAULT NULL COMMENT 'Imagen del recurso',
  `rec_estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo' COMMENT 'Estado del recurso (activo/inactivo)',
  `rec_lec_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID de la lección asociada',
  `rec_icono` varchar(255) DEFAULT NULL COMMENT 'Ícono representativo del recurso',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `redes_sociales`
--

CREATE TABLE `redes_sociales` (
  `red_id` int(10) UNSIGNED NOT NULL COMMENT 'ID de la red social',
  `red_nombre` varchar(255) NOT NULL COMMENT 'Nombre de la red social',
  `red_enlace` varchar(255) NOT NULL COMMENT 'Enlace asociado a la red social',
  `red_perfil` varchar(100) DEFAULT NULL COMMENT 'Perfil de la red social',
  `red_publicada` enum('SI','NO') NOT NULL DEFAULT 'NO' COMMENT 'Indica si la red social está publicada',
  `red_categoria` enum('Redes sociales principales','Redes sociales de mensajería','Plataformas de videoconferencia','Redes sociales profesionales','Redes sociales populares en China','Redes sociales alternativas','Plataformas para eventos y reuniones','Plataformas de streaming de audio','Plataformas de arte y diseño','Plataformas de fotografía','Directorios y reseñas de negocios','Redes sociales para amantes de la lectura','Plataformas de música en streaming','Otras redes sociales') DEFAULT NULL,
  `red_icono` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `redes_sociales`
--

INSERT INTO `redes_sociales` (`red_id`, `red_nombre`, `red_enlace`, `red_perfil`, `red_publicada`, `red_categoria`, `red_icono`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'Facebook', 'www.facebook.com', 'ecolens', 'SI', 'Redes sociales principales', 'fab fa-facebook|#1877F2', '1900-01-01 00:00:00', '2025-10-29 12:00:42', 1, 1),
(2, 'Instagram', 'www.instagram.com', 'ecolens', 'SI', 'Redes sociales principales', 'fab fa-instagram|#E1306C', '1900-01-01 00:00:00', '2025-10-29 12:00:51', 1, 1),
(3, 'LinkedIn', 'www.linkedin.com/in', NULL, 'NO', 'Redes sociales principales', 'fab fa-linkedin|#0077B5', '1900-01-01 00:00:00', '2025-10-28 12:27:31', 1, 1),
(4, 'Spotify', 'open.spotify.com/', NULL, 'NO', 'Plataformas de streaming de audio', 'fab fa-spotify|#1DB954', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(5, 'Telegram', 'www.telegram.org', NULL, 'NO', 'Redes sociales principales', 'fab fa-telegram|#0088CC', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(6, 'TikTok', 'www.tiktok.com', NULL, 'NO', 'Redes sociales principales', 'fab fa-tiktok|#000000', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(7, 'Twitter', 'twitter.com', NULL, 'NO', 'Redes sociales principales', 'fab fa-twitter|#1DA1F2', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(8, 'WhatsApp', 'wa.me', NULL, 'NO', 'Redes sociales principales', 'fab fa-whatsapp|#25D366', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(9, 'YouTube', 'www.youtube.com', NULL, 'NO', 'Redes sociales principales', 'fab fa-youtube|#FF0000', '1900-01-01 00:00:00', '2025-10-28 12:27:30', 1, 1),
(10, 'Zoom', 'www.zoom.us', NULL, 'NO', 'Plataformas de videoconferencia', 'fas fa-video|#2D8CFF', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `rol_id` int(10) UNSIGNED NOT NULL COMMENT 'Identificador único de Roles',
  `rol_nombre` varchar(45) NOT NULL COMMENT 'Nombre del Rol',
  `rol_descripcion` varchar(100) DEFAULT NULL COMMENT 'Descripción del Rol',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`rol_id`, `rol_nombre`, `rol_descripcion`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'SuperAdministrador', NULL, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(2, 'Administrador', NULL, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(3, 'Usuario', NULL, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `ser_id` int(10) UNSIGNED NOT NULL COMMENT 'Identificador único de Servicios',
  `ser_titulo` varchar(100) NOT NULL COMMENT 'Titulo del Servicio',
  `ser_slug` varchar(255) DEFAULT NULL COMMENT 'Slug del servicio',
  `ser_resumen` varchar(300) DEFAULT NULL COMMENT 'Resumen del Servicio',
  `ser_cuerpo` mediumtext DEFAULT NULL COMMENT 'Cuerpo del Servicio',
  `ser_publicado` enum('SI','NO') NOT NULL COMMENT ' 	Estado de publicación del Servicio ',
  `ser_destacado` enum('NO','SI') NOT NULL COMMENT '¿Servicio Destacado?',
  `ser_imagen` varchar(255) DEFAULT NULL COMMENT 'Imagen del Servicio',
  `ser_icono` varchar(100) DEFAULT NULL COMMENT 'Ícono del Servicio',
  `ser_cat_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'Categoría del Servicio',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `taxonomias`
--

CREATE TABLE `taxonomias` (
  `tax_id` int(10) UNSIGNED NOT NULL COMMENT 'Identificador único de la clase taxonómica',
  `tax_nombre` varchar(150) NOT NULL COMMENT 'Nombre científico',
  `tax_nombre_comun` varchar(255) DEFAULT NULL COMMENT 'Nombre común o local del grupo taxonómico',
  `tax_slug` varchar(255) DEFAULT NULL COMMENT 'URL de la taxonomía',
  `tax_descripcion` text DEFAULT NULL COMMENT 'Descripción general del grupo taxonómico',
  `tax_imagen` varchar(255) DEFAULT NULL COMMENT 'Imagen representativa o ícono del grupo',
  `tax_estado` enum('activo','inactivo') DEFAULT 'activo' COMMENT 'Estado de la Taxonomía',
  `created_at` datetime DEFAULT current_timestamp() COMMENT 'Fecha de creación del registro',
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Fecha de última modificación',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'Usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'Usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Clases taxonómicas principales del sistema EcoLens';

--
-- Volcado de datos para la tabla `taxonomias`
--

INSERT INTO `taxonomias` (`tax_id`, `tax_nombre`, `tax_nombre_comun`, `tax_slug`, `tax_descripcion`, `tax_imagen`, `tax_estado`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'Actinopterygii', 'Peces óseos', 'peces-oseos', '<p class=\"p1\">Con sus cuerpos esbeltos, aletas radiadas y colores vibrantes, los peces &oacute;seos habitan desde riachuelos andinos hasta las profundidades del Pac&iacute;fico. Este grupo es el m&aacute;s diverso de los vertebrados, y en Chile representa una riqueza biol&oacute;gica que conecta nuestras aguas dulces y saladas. Observarlos es descubrir un mundo acu&aacute;tico en constante movimiento, donde la evoluci&oacute;n ha tallado verdaderas obras de arte vivientes.</p>', 'taxonomias/1.jpg', 'inactivo', '2025-10-16 11:16:55', '2025-11-11 22:15:01', 1, 1),
(2, 'Amphibia', 'Anfibios', 'anfibios', '<p>Aqu&iacute; va la descripci&oacute;n de Amphibia.</p>', 'taxonomias/2.jpg', 'inactivo', '2025-10-16 11:16:55', '2025-11-11 22:10:18', 1, 1),
(3, 'Arachnida', 'Arácnidos', 'aracnidos', '<p>Aqu&iacute; va la descripci&oacute;n de Arachnida.</p>', 'taxonomias/3.jpg', 'inactivo', '2025-10-16 11:16:55', '2025-11-11 22:08:33', 1, 1),
(4, 'Aves', 'Aves', 'aves', '<p>Las aves son uno de los grupos m&aacute;s visibles y diversos de la fauna chilena. Desde los humedales costeros hasta los bosques australes, ocupan casi todos los h&aacute;bitats del pa&iacute;s, cumpliendo roles clave como dispersoras de semillas, controladoras de insectos y bioindicadoras de la salud de los ecosistemas.</p>\r\n<p>En EcoLens, este grupo taxon&oacute;mico re&uacute;ne a las especies de aves que el modelo es capaz de identificar a partir de fotograf&iacute;as, con &eacute;nfasis en especies nativas y propias del territorio chileno. Cada detecci&oacute;n busca acercar a las personas a la observaci&oacute;n responsable de la naturaleza, promoviendo el conocimiento y la valoraci&oacute;n de nuestro patrimonio avifaun&iacute;stico.</p>\r\n<p>Explorar este grupo permite conocer mejor la diversidad de formas, colores y comportamientos presentes en las aves de Chile, y entender c&oacute;mo la inteligencia artificial puede apoyar la educaci&oacute;n ambiental y el monitoreo de la biodiversidad.</p>', 'taxonomias/4.jpg', 'activo', '2025-10-16 11:16:55', '2025-12-02 15:31:04', 1, 1),
(5, 'Insecta', 'Insectos', 'insectos', '<p>Aqu&iacute; va la descripci&oacute;n de Insecta.</p>', 'taxonomias/5.jpg', 'inactivo', '2025-10-16 11:16:55', '2025-11-11 22:11:10', 1, 1),
(6, 'Mammalia', 'Mamíferos', 'mamiferos', '<p>Los mam&iacute;feros representan uno de los grupos m&aacute;s emblem&aacute;ticos de la fauna chilena. Incluyen desde peque&ntilde;os roedores y marsupiales end&eacute;micos de los bosques templados, hasta grandes herb&iacute;voros y carn&iacute;voros que habitan cordilleras, estepas y zonas costeras. Suelen ocupar posiciones clave en las cadenas tr&oacute;ficas y son especialmente sensibles a la fragmentaci&oacute;n de h&aacute;bitat y a la presi&oacute;n humana.</p>\r\n<p>En EcoLens, este grupo taxon&oacute;mico re&uacute;ne a los mam&iacute;feros que el modelo es capaz de reconocer a partir de im&aacute;genes, con foco en especies presentes en Chile. El sistema utiliza un modelo experto entrenado espec&iacute;ficamente para este grupo, lo que permite lograr altos niveles de precisi&oacute;n en la identificaci&oacute;n de especies a partir de fotograf&iacute;as tomadas en terreno.</p>\r\n<p>Al recorrer las especies clasificadas dentro de este grupo, se busca no solo mostrar la diversidad de mam&iacute;feros del pa&iacute;s, sino tambi&eacute;n apoyar iniciativas de educaci&oacute;n, investigaci&oacute;n y conservaci&oacute;n que contribuyan a protegerlos en sus h&aacute;bitats naturales.</p>', 'taxonomias/6.jpg', 'activo', '2025-10-16 11:16:55', '2025-12-02 15:30:47', 1, 1),
(7, 'Reptilia', 'Reptiles', 'reptiles', '<p>Aqu&iacute; va la descripci&oacute;n de Reptilia.</p>', 'taxonomias/7.jpg', 'inactivo', '2025-10-16 11:16:55', '2025-11-11 22:11:57', 1, 1),
(8, 'Crustacea', 'Crustáceos', 'crustaceos', '<p>Aqu&iacute; va la descripci&oacute;n de Crustacea.</p>', 'taxonomias/8.jpg', 'inactivo', '2025-10-16 11:16:55', '2025-11-11 22:12:41', 1, 1),
(9, 'Mollusca', 'Moluscos', 'moluscos', '<p>Aqu&iacute; va la descripci&oacute;n de Mollusca.</p>', 'taxonomias/9.jpg', 'inactivo', '2025-10-16 11:16:55', '2025-11-11 22:13:55', 1, 1),
(10, 'Echinodermata', 'Equinodermos', 'equinodermos', '<p>Aqu&iacute; va la descripci&oacute;n de Echinodermata.</p>', 'taxonomias/10.jpg', 'inactivo', '2025-10-16 11:16:55', '2025-11-11 22:14:34', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `testimonios`
--

CREATE TABLE `testimonios` (
  `tes_id` int(10) UNSIGNED NOT NULL COMMENT 'ID único del testimonio',
  `tes_nombre` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT 'Nombre de la persona que da el testimonio',
  `tes_cargo` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL COMMENT 'Cargo o rol de la persona (opcional)',
  `tes_empresa` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL COMMENT 'Empresa o institución (opcional)',
  `tes_testimonio` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT 'Contenido del testimonio',
  `tes_imagen` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL COMMENT 'URL o nombre de archivo de la imagen del testimonio',
  `tes_orden` int(10) UNSIGNED DEFAULT 0 COMMENT 'Orden de visualización',
  `tes_estado` enum('borrador','publicado') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'borrador' COMMENT 'Estado del testimonio',
  `tes_slug` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL COMMENT 'Slug único para acceso o referencia interna',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Testimonios o recomendaciones para mostrar en el sitio';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajadores`
--

CREATE TABLE `trabajadores` (
  `tra_id` int(10) UNSIGNED NOT NULL COMMENT 'Identificador único del trabajador',
  `tra_nombre` varchar(255) NOT NULL,
  `tra_apellido` varchar(255) NOT NULL,
  `tra_cedula` varchar(20) NOT NULL,
  `tra_fecha_nacimiento` date DEFAULT NULL COMMENT 'Fecha de nacimiento del trabajador',
  `tra_genero` enum('Masculino','Femenino','Otro') DEFAULT NULL COMMENT 'Género del trabajador',
  `tra_puesto` varchar(100) DEFAULT NULL COMMENT 'Puesto de trabajo del trabajador',
  `tra_departamento` varchar(100) DEFAULT NULL COMMENT 'Departamento o área en la que trabaja el trabajador',
  `tra_fecha_contratacion` date DEFAULT NULL COMMENT 'Fecha de contratación del trabajador',
  `tra_salario` decimal(10,2) DEFAULT NULL COMMENT 'Salario del trabajador',
  `tra_email` varchar(255) DEFAULT NULL COMMENT 'Correo electrónico del trabajador',
  `tra_telefono` varchar(20) DEFAULT NULL COMMENT 'Número de teléfono del trabajador',
  `tra_direccion` text DEFAULT NULL COMMENT 'Dirección del trabajador',
  `tra_foto_perfil` varchar(255) DEFAULT NULL COMMENT 'Ruta de la foto de perfil del trabajador',
  `tra_descripcion` text DEFAULT NULL COMMENT 'Descripción o perfil del trabajador',
  `tra_facebook` varchar(255) DEFAULT NULL COMMENT 'Enlace al perfil de Facebook del trabajador',
  `tra_instagram` varchar(255) DEFAULT NULL COMMENT 'Enlace al perfil de Instagram del trabajador',
  `tra_linkedin` varchar(255) DEFAULT NULL COMMENT 'Enlace al perfil de LinkedIn del trabajador',
  `tra_tiktok` varchar(255) DEFAULT NULL COMMENT 'Enlace al perfil de TikTok del trabajador',
  `tra_twitter` varchar(255) DEFAULT NULL COMMENT 'Enlace al perfil de Twitter del trabajador',
  `tra_whatsapp` varchar(255) DEFAULT NULL COMMENT 'Número de WhatsApp del trabajador',
  `tra_modalidad_contrato` enum('Plazo Fijo','Indefinido','A Demanda') NOT NULL DEFAULT 'Indefinido',
  `tra_publicado` enum('SI','NO') NOT NULL DEFAULT 'NO',
  `tra_estado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabla de trabajadores en la empresa';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `usu_id` int(10) UNSIGNED NOT NULL COMMENT 'Identificador único de Usuario',
  `usu_username` varchar(50) NOT NULL COMMENT 'Nombre de Usuario',
  `usu_email` varchar(80) NOT NULL COMMENT 'Email del Usuario',
  `usu_email_verificado` enum('SI','NO') NOT NULL DEFAULT 'NO' COMMENT 'Indica si el correo electrónico ha sido verificado',
  `usu_password` varchar(250) NOT NULL COMMENT 'Password del Usuario',
  `usu_authKey` varchar(250) NOT NULL COMMENT 'Llave de autenticación del Usuario',
  `usu_accessToken` varchar(250) NOT NULL COMMENT 'Token de acceso del Usuario',
  `usu_activate` enum('SI','NO') DEFAULT 'NO' COMMENT 'Estado de activación del usuario',
  `usu_imagen` varchar(255) DEFAULT NULL COMMENT 'Imagen del Usuario',
  `usu_nombres` varchar(100) DEFAULT NULL COMMENT 'Nombres del Usuario',
  `usu_apellidos` varchar(100) DEFAULT NULL COMMENT 'Apellido del Usuario',
  `usu_telefono` varchar(100) DEFAULT NULL COMMENT 'Teléfono del Usuario',
  `usu_ubicacion` varchar(50) NOT NULL DEFAULT 'Santiago, CL' COMMENT 'Ubicación por defecto del usuario',
  `usu_rol_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'Rol del Usuario',
  `usu_letra` int(2) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usu_id`, `usu_username`, `usu_email`, `usu_email_verificado`, `usu_password`, `usu_authKey`, `usu_accessToken`, `usu_activate`, `usu_imagen`, `usu_nombres`, `usu_apellidos`, `usu_telefono`, `usu_ubicacion`, `usu_rol_id`, `usu_letra`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'admin', 'admin@capsulatech.cl', 'SI', 'fszRrCnGrRgQA', '8f23d8ca137ee3474f6363fb6f5fbcb9a5bd41e6d0ab3502ebd44b2b7934c9ee1375e176f67ce09ef8c0b41498c4f9b119cb67f9d1ed519b07f374058807b83b98a58e6def69e87ab19b5d57478f4912211f48c1a2c78531b541873fb4dbfe9baaec2f05', 'f7b27f1d8f91285cae154134d6bf8010cde2f203c7d996e4c1bab8a8be9a64ea24d0871df947fbf8a4ac806cc8cb5b60d0bf710b465005af6bd63b3667e625edc212fd46aa38aa780c797a8ec156b30f0ddf4a2803f8e6170a7904ec3f6014cc796bc42c', 'SI', 'users/1.png', 'Super', 'Administrador', '', 'Santiago, CL', 1, 13, '1900-01-01 00:00:00', '2025-10-27 22:12:29', 1, 1),
(2, 'Admin_Ecolens', 'admin@ecolens.cl', 'SI', 'saRLM1X9BnaDo', 'e40bc95650e121d398e2a0099f1455d5de4f317198263b1b24921764dddb97699e4398be0ca924f4e0851093949589ef1508700c9ad50f109713401d201eae5286de5767865bcbdf2da89db2a467f1691912d18602694e0dba068d1e26cee8b56bd5cc5e', '2a988d3a168d834a8689d2e1d0a378b0d7523c4e20e0d740722dbf44c35bf9158667d8188619aaa339229ed903558941b37a7de7ea3e105f977f53d2fc8195ac8a206adb37570e347c301f09e9caa7b343324612b65faf0b377d42be40f7eb3be394217d', 'NO', NULL, NULL, NULL, NULL, 'Santiago, CL', 2, 15, NULL, NULL, NULL, NULL),
(3, 'rmunozm', 'rogeliomunozmunoz@outlook.com', 'SI', 'fsW0oyAmQg992', 'b02e4eba380825e59c990d88a932857ab23e926c2b39d0e07283a51ad33a7480a9828c0b81e3952e60c277aa0a3687c797c7135ff3f6d614e3177e64881e5a724b4ab388ba47a403746d32f015e54bdb1a17bc4357569579281ff9a10258236a9df2f7c5', 'cdf9406894618d2d0daba06831e3da63dcb2e5c19293ff77ee2a219047d8054c89c358ea8c5623028f8217682d4417ba6d556987a20db7e6860470e5041faf7c6cb5b0320eca118d22b770fb2bfe46a75bf3b32aa3edec08451f60361200cd315fae6691', 'SI', 'users/3.jpeg', 'ROGELIO ERNESTO', 'MUÑOZ MUÑOZ', '967266184', 'Santiago, CL', 3, 10, '2025-10-27 21:34:15', '2025-10-27 22:35:01', 1, 3),
(5, 'vsorianof', 'valeriapaz.sf@gmail.com', 'SI', 'fs1mxPy0Jha92', 'b26e7dd2966802f873962c190e304597369176c8505137d84afbd556977da024acd1313b8440a48284c1436fe7aa66bfee8d2fb86b2314c0c918c4d3a0cc46e0900000d4c73642b4505e10ff1a67faac423e5f2e682d0662fe1d1f2132c86857ee7eea0f', '076c235a11cad6887011dce950ff1c6436dc278fcbb8ab0a8f9a8ff4eae97bf78e79a3a0ee2aaf2c2cec6d4c9105d8bdc1a655522d8e7bdc7864ae44105069278cc69b7329f5c2a16d33a7b95eab5ed6287d94ec470a8a169f9d119ab7ce67fad2a36e84', 'SI', '', NULL, NULL, NULL, 'Santiago, CL', 1, 10, '2025-10-28 21:13:27', '2025-10-28 21:29:43', 1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `articulos`
--
ALTER TABLE `articulos`
  ADD PRIMARY KEY (`art_id`);

--
-- Indices de la tabla `asuntos`
--
ALTER TABLE `asuntos`
  ADD PRIMARY KEY (`asu_id`);

--
-- Indices de la tabla `categorias_opciones`
--
ALTER TABLE `categorias_opciones`
  ADD PRIMARY KEY (`cat_id`),
  ADD UNIQUE KEY `cat_nombre_UNIQUE` (`cat_nombre`);

--
-- Indices de la tabla `categoria_articulo`
--
ALTER TABLE `categoria_articulo`
  ADD PRIMARY KEY (`caa_id`);

--
-- Indices de la tabla `categoria_servicios`
--
ALTER TABLE `categoria_servicios`
  ADD PRIMARY KEY (`cas_id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`cli_id`),
  ADD UNIQUE KEY `cli_slug` (`cli_slug`);

--
-- Indices de la tabla `colores`
--
ALTER TABLE `colores`
  ADD PRIMARY KEY (`col_id`);

--
-- Indices de la tabla `correos_electronicos`
--
ALTER TABLE `correos_electronicos`
  ADD PRIMARY KEY (`cor_id`);

--
-- Indices de la tabla `curriculum`
--
ALTER TABLE `curriculum`
  ADD PRIMARY KEY (`cur_id`),
  ADD UNIQUE KEY `uq_cur_per_id` (`cur_per_id`),
  ADD UNIQUE KEY `uq_singleton` (`singleton`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`cur_id`);

--
-- Indices de la tabla `detecciones`
--
ALTER TABLE `detecciones`
  ADD PRIMARY KEY (`det_id`),
  ADD KEY `idx_tax` (`det_tax_id`),
  ADD KEY `idx_esp` (`det_esp_id`),
  ADD KEY `idx_usuario` (`det_obs_id`),
  ADD KEY `idx_modelo_router` (`det_modelo_router_id`),
  ADD KEY `idx_modelo_experto` (`det_modelo_experto_id`),
  ADD KEY `idx_fb_tax` (`det_fb_tax_id`);

--
-- Indices de la tabla `dispositivos`
--
ALTER TABLE `dispositivos`
  ADD PRIMARY KEY (`dis_id`);

--
-- Indices de la tabla `emails`
--
ALTER TABLE `emails`
  ADD PRIMARY KEY (`ema_id`),
  ADD UNIQUE KEY `ema_id_UNIQUE` (`ema_id`);

--
-- Indices de la tabla `especies`
--
ALTER TABLE `especies`
  ADD PRIMARY KEY (`esp_id`);

--
-- Indices de la tabla `experiencias`
--
ALTER TABLE `experiencias`
  ADD PRIMARY KEY (`exp_id`);

--
-- Indices de la tabla `formacion`
--
ALTER TABLE `formacion`
  ADD PRIMARY KEY (`for_id`);

--
-- Indices de la tabla `galerias`
--
ALTER TABLE `galerias`
  ADD PRIMARY KEY (`gal_id`);

--
-- Indices de la tabla `habilidades`
--
ALTER TABLE `habilidades`
  ADD PRIMARY KEY (`hab_id`);

--
-- Indices de la tabla `herramientas`
--
ALTER TABLE `herramientas`
  ADD PRIMARY KEY (`her_id`);

--
-- Indices de la tabla `imagenes_galeria`
--
ALTER TABLE `imagenes_galeria`
  ADD PRIMARY KEY (`img_id`);

--
-- Indices de la tabla `layouts`
--
ALTER TABLE `layouts`
  ADD PRIMARY KEY (`lay_id`);

--
-- Indices de la tabla `lecciones`
--
ALTER TABLE `lecciones`
  ADD PRIMARY KEY (`lec_id`);

--
-- Indices de la tabla `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`med_id`),
  ADD UNIQUE KEY `med_ruta_unique` (`med_ruta`),
  ADD UNIQUE KEY `ux_media_unico` (`med_entidad`,`med_tipo`,`med_registro`,`med_orden`);

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`men_id`);

--
-- Indices de la tabla `modalidad`
--
ALTER TABLE `modalidad`
  ADD PRIMARY KEY (`mod_id`);

--
-- Indices de la tabla `modelos`
--
ALTER TABLE `modelos`
  ADD PRIMARY KEY (`mod_id`);

--
-- Indices de la tabla `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`mod_id`);

--
-- Indices de la tabla `newsletter`
--
ALTER TABLE `newsletter`
  ADD PRIMARY KEY (`new_id`),
  ADD UNIQUE KEY `new_email` (`new_email`),
  ADD UNIQUE KEY `new_email_2` (`new_email`),
  ADD UNIQUE KEY `new_email_3` (`new_email`);

--
-- Indices de la tabla `observadores`
--
ALTER TABLE `observadores`
  ADD PRIMARY KEY (`obs_id`),
  ADD KEY `idx_observadores_act_token` (`obs_act_token_hash`),
  ADD KEY `idx_observadores_act_expires` (`obs_act_expires`);

--
-- Indices de la tabla `opciones`
--
ALTER TABLE `opciones`
  ADD PRIMARY KEY (`opc_id`),
  ADD UNIQUE KEY `opc_nombre_UNIQUE` (`opc_nombre`),
  ADD KEY `idx_opciones_cat` (`opc_cat_id`),
  ADD KEY `idx_opciones_rol` (`opc_rol_id`);

--
-- Indices de la tabla `paginas`
--
ALTER TABLE `paginas`
  ADD PRIMARY KEY (`pag_id`),
  ADD UNIQUE KEY `uniq_titulo` (`pag_titulo`),
  ADD KEY `idx_autor_id` (`pag_autor_id`);

--
-- Indices de la tabla `perfil`
--
ALTER TABLE `perfil`
  ADD PRIMARY KEY (`per_id`),
  ADD UNIQUE KEY `uq_singleton` (`singleton`);

--
-- Indices de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  ADD PRIMARY KEY (`pro_id`),
  ADD UNIQUE KEY `pro_slug` (`pro_slug`);

--
-- Indices de la tabla `recursos`
--
ALTER TABLE `recursos`
  ADD PRIMARY KEY (`rec_id`);

--
-- Indices de la tabla `redes_sociales`
--
ALTER TABLE `redes_sociales`
  ADD PRIMARY KEY (`red_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`rol_id`),
  ADD UNIQUE KEY `rol_id_UNIQUE` (`rol_id`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`ser_id`);

--
-- Indices de la tabla `taxonomias`
--
ALTER TABLE `taxonomias`
  ADD PRIMARY KEY (`tax_id`);

--
-- Indices de la tabla `testimonios`
--
ALTER TABLE `testimonios`
  ADD PRIMARY KEY (`tes_id`);

--
-- Indices de la tabla `trabajadores`
--
ALTER TABLE `trabajadores`
  ADD PRIMARY KEY (`tra_id`),
  ADD UNIQUE KEY `tra_cedula` (`tra_cedula`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usu_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `articulos`
--
ALTER TABLE `articulos`
  MODIFY `art_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único del artículo';

--
-- AUTO_INCREMENT de la tabla `asuntos`
--
ALTER TABLE `asuntos`
  MODIFY `asu_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID del asunto', AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `categorias_opciones`
--
ALTER TABLE `categorias_opciones`
  MODIFY `cat_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de la categoría de opción', AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `categoria_articulo`
--
ALTER TABLE `categoria_articulo`
  MODIFY `caa_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único de la categoría', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `categoria_servicios`
--
ALTER TABLE `categoria_servicios`
  MODIFY `cas_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID de la categoría de servicios', AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `cli_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID del Cliente';

--
-- AUTO_INCREMENT de la tabla `colores`
--
ALTER TABLE `colores`
  MODIFY `col_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `correos_electronicos`
--
ALTER TABLE `correos_electronicos`
  MODIFY `cor_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID del correo electrónico', AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `curriculum`
--
ALTER TABLE `curriculum`
  MODIFY `cur_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Llave primaria del curriculum', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `cur_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único del curso';

--
-- AUTO_INCREMENT de la tabla `detecciones`
--
ALTER TABLE `detecciones`
  MODIFY `det_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de la detección', AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT de la tabla `dispositivos`
--
ALTER TABLE `dispositivos`
  MODIFY `dis_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del dispositivo registrado';

--
-- AUTO_INCREMENT de la tabla `emails`
--
ALTER TABLE `emails`
  MODIFY `ema_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de Emails';

--
-- AUTO_INCREMENT de la tabla `especies`
--
ALTER TABLE `especies`
  MODIFY `esp_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de la especie', AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `experiencias`
--
ALTER TABLE `experiencias`
  MODIFY `exp_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID de la experiencia';

--
-- AUTO_INCREMENT de la tabla `formacion`
--
ALTER TABLE `formacion`
  MODIFY `for_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID de la formación';

--
-- AUTO_INCREMENT de la tabla `galerias`
--
ALTER TABLE `galerias`
  MODIFY `gal_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de la galería', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `habilidades`
--
ALTER TABLE `habilidades`
  MODIFY `hab_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID de la habilidad';

--
-- AUTO_INCREMENT de la tabla `herramientas`
--
ALTER TABLE `herramientas`
  MODIFY `her_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID de la herramienta';

--
-- AUTO_INCREMENT de la tabla `imagenes_galeria`
--
ALTER TABLE `imagenes_galeria`
  MODIFY `img_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de la imagen', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `layouts`
--
ALTER TABLE `layouts`
  MODIFY `lay_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `lecciones`
--
ALTER TABLE `lecciones`
  MODIFY `lec_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único de la lección';

--
-- AUTO_INCREMENT de la tabla `media`
--
ALTER TABLE `media`
  MODIFY `med_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del medio', AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `men_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del menú', AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT de la tabla `modalidad`
--
ALTER TABLE `modalidad`
  MODIFY `mod_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de modalidad', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `modelos`
--
ALTER TABLE `modelos`
  MODIFY `mod_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del modelo IA', AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `modulos`
--
ALTER TABLE `modulos`
  MODIFY `mod_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único del módulo';

--
-- AUTO_INCREMENT de la tabla `newsletter`
--
ALTER TABLE `newsletter`
  MODIFY `new_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único del suscriptor';

--
-- AUTO_INCREMENT de la tabla `observadores`
--
ALTER TABLE `observadores`
  MODIFY `obs_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del observador o usuario de EcoLens', AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `opciones`
--
ALTER TABLE `opciones`
  MODIFY `opc_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de la opción', AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT de la tabla `paginas`
--
ALTER TABLE `paginas`
  MODIFY `pag_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único de la página', AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `perfil`
--
ALTER TABLE `perfil`
  MODIFY `per_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID del Perfil', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  MODIFY `pro_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del proyecto';

--
-- AUTO_INCREMENT de la tabla `recursos`
--
ALTER TABLE `recursos`
  MODIFY `rec_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único del recurso';

--
-- AUTO_INCREMENT de la tabla `redes_sociales`
--
ALTER TABLE `redes_sociales`
  MODIFY `red_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID de la red social', AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `rol_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de Roles', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `ser_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de Servicios';

--
-- AUTO_INCREMENT de la tabla `taxonomias`
--
ALTER TABLE `taxonomias`
  MODIFY `tax_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de la clase taxonómica', AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `testimonios`
--
ALTER TABLE `testimonios`
  MODIFY `tes_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único del testimonio';

--
-- AUTO_INCREMENT de la tabla `trabajadores`
--
ALTER TABLE `trabajadores`
  MODIFY `tra_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del trabajador';

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usu_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de Usuario', AUTO_INCREMENT=6;

-- --------------------------------------------------------

--
-- Estructura para la vista `actividad_reciente`
--
DROP TABLE IF EXISTS `actividad_reciente`;

CREATE ALGORITHM=UNDEFINED DEFINER=`ecol_admin_ecolens`@`localhost` SQL SECURITY DEFINER VIEW `actividad_reciente`  AS SELECT `unioned`.`tabla` AS `tabla`, `unioned`.`id` AS `id`, `unioned`.`updated_by` AS `updated_by`, `unioned`.`updated_at` AS `updated_at`, `unioned`.`nombre_registro` AS `nombre_registro` FROM (select 'articulos' AS `tabla`,`articulos`.`art_id` AS `id`,`articulos`.`updated_by` AS `updated_by`,`articulos`.`updated_at` AS `updated_at`,`articulos`.`art_titulo` AS `nombre_registro` from `articulos` union all select 'paginas' AS `tabla`,`paginas`.`pag_id` AS `id`,`paginas`.`updated_by` AS `updated_by`,`paginas`.`updated_at` AS `updated_at`,`paginas`.`pag_titulo` AS `nombre_registro` from `paginas` union all select 'proyectos' AS `tabla`,`proyectos`.`pro_id` AS `id`,`proyectos`.`updated_by` AS `updated_by`,`proyectos`.`updated_at` AS `updated_at`,`proyectos`.`pro_titulo` AS `nombre_registro` from `proyectos` union all select 'clientes' AS `tabla`,`clientes`.`cli_id` AS `id`,`clientes`.`updated_by` AS `updated_by`,`clientes`.`updated_at` AS `updated_at`,`clientes`.`cli_logo` AS `nombre_registro` from `clientes` union all select 'servicios' AS `tabla`,`servicios`.`ser_id` AS `id`,`servicios`.`updated_by` AS `updated_by`,`servicios`.`updated_at` AS `updated_at`,`servicios`.`ser_titulo` AS `nombre_registro` from `servicios`) AS `unioned` ORDER BY `unioned`.`updated_at` DESC LIMIT 0, 100 ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
