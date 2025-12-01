-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:8889
-- Tiempo de generación: 19-06-2025 a las 20:57:48
-- Versión del servidor: 5.7.39
-- Versión de PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `CMS_V5_FINAL`
--

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `actividad_reciente`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `actividad_reciente` (
`tabla` varchar(9)
,`id` int(11) unsigned
,`updated_by` int(11) unsigned
,`updated_at` datetime
,`nombre_registro` varchar(255)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos`
--

CREATE TABLE `articulos` (
  `art_id` int(10) UNSIGNED NOT NULL COMMENT 'ID único del artículo',
  `art_titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Título del artículo',
  `art_contenido` longtext COLLATE utf8mb4_unicode_ci COMMENT 'Contenido HTML del artículo',
  `art_resumen` text COLLATE utf8mb4_unicode_ci COMMENT 'Resumen o descripción del artículo',
  `art_etiquetas` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Palabras clave o etiquetas del artículo',
  `art_fecha_publicacion` datetime DEFAULT NULL,
  `art_destacado` enum('SI','NO') COLLATE utf8mb4_unicode_ci DEFAULT 'NO' COMMENT 'Indicador para marcar el artículo como destacado',
  `art_vistas` int(10) UNSIGNED DEFAULT '0' COMMENT 'Número de veces que se ha visto el artículo',
  `art_likes` int(10) UNSIGNED DEFAULT '0' COMMENT 'Número de "me gusta" del artículo',
  `art_comentarios_habilitados` enum('SI','NO') COLLATE utf8mb4_unicode_ci DEFAULT 'SI' COMMENT 'Indicador para permitir comentarios en el artículo',
  `art_palabras_clave` text COLLATE utf8mb4_unicode_ci COMMENT 'Palabras clave relacionadas con el contenido del artículo',
  `art_meta_descripcion` text COLLATE utf8mb4_unicode_ci COMMENT 'Meta descripción del artículo',
  `art_slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Slug del artículo (único)',
  `art_estado` enum('borrador','publicado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'borrador' COMMENT 'Estado del artículo',
  `art_categoria_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID de la categoría del artículo',
  `art_notificacion` enum('SI','NO') COLLATE utf8mb4_unicode_ci DEFAULT 'SI' COMMENT '¿Notificar a suscriptores?',
  `art_imagen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL de la imagen principal del artículo',
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
  `asu_nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del asunto',
  `asu_publicado` enum('SI','NO') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Indicador de si el asunto está publicado',
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
  `cat_orden` int(10) UNSIGNED DEFAULT '1' COMMENT 'Orden de visualización en el panel',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de creación del registro',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de última modificación',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó la categoría',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que modificó por última vez la categoría'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Categorías para agrupar opciones del sistema';

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
  `caa_nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre de la categoría',
  `caa_slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Slug de la categoría',
  `caa_estado` enum('publicado','borrador') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'borrador' COMMENT 'Estado de la categoría (publicado/borrador)',
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
  `cas_nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre de la categoría de servicios',
  `cas_publicada` enum('SI','NO') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Estado de publicación de la categoría de servicios',
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
  `cli_nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del Cliente',
  `cli_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Correo Electrónico del Cliente',
  `cli_telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Teléfono del Cliente',
  `cli_direccion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Dirección del Cliente',
  `cli_estado` enum('SI','NO') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'SI' COMMENT 'Estado del Cliente (SI/NO)',
  `cli_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ruta del Logotipo del Cliente',
  `cli_publicado` enum('SI','NO') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'SI' COMMENT 'Publicado (SI/NO)',
  `cli_destacado` enum('SI','NO') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NO' COMMENT 'Destacado (SI/NO)',
  `cli_slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Slug del cliente ',
  `cli_descripcion` text COLLATE utf8mb4_unicode_ci COMMENT 'Descripción del Cliente',
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
  `col_nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `col_valor` mediumtext COLLATE utf8mb4_unicode_ci,
  `col_descripcion` mediumtext COLLATE utf8mb4_unicode_ci,
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
  `cor_nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del remitente',
  `cor_correo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Correo electrónico del remitente',
  `cor_asunto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Asunto del correo',
  `cor_mensaje` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Cuerpo del correo electrónico',
  `cor_fecha_consulta` datetime DEFAULT NULL COMMENT 'Fecha de consulta del correo electrónico',
  `cor_fecha_respuesta` datetime DEFAULT NULL COMMENT 'Fecha de respuesta del correo electrónico',
  `cor_estado` enum('pendiente','resuelto') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente' COMMENT 'Estado del correo electrónico',
  `cor_respuesta` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'Campo para almacenar la respuesta al correo electrónico',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curriculum`
--

CREATE TABLE `curriculum` (
  `cur_id` int(10) UNSIGNED NOT NULL COMMENT 'Llave primaria del curriculum',
  `cur_per_id` int(10) UNSIGNED NOT NULL COMMENT 'Referencia al perfil (per_id)',
  `cur_titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Título del Curriculum (ej. "Currículum Vitae")',
  `cur_subtitulo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Subtítulo o tagline opcional',
  `cur_casa_estudio` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Casa de estudios indicada en el curriculum',
  `cur_resumen_profesional` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'Resumen profesional extendido para el CV',
  `cur_estilos` text COLLATE utf8mb4_unicode_ci COMMENT 'Configuración adicional (por ejemplo, JSON o CSS para personalizar el CV)',
  `cur_contenido` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'Contenido o plantilla del CV',
  `singleton` tinyint(1) NOT NULL DEFAULT '1',
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
  `cur_titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Título del curso',
  `cur_descripcion` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'Descripción general del curso',
  `cur_imagen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Imagen de portada del curso',
  `cur_estado` enum('borrador','publicado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'borrador' COMMENT 'Estado del curso',
  `cur_slug` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Slug del curso para URL amigable',
  `cur_icono` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ícono representativo del curso',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `emails`
--

CREATE TABLE `emails` (
  `ema_id` int(10) UNSIGNED NOT NULL COMMENT 'Identificador único de Emails',
  `ema_asunto` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Asunto del Email',
  `ema_cuerpo` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'Cuerpo del Email',
  `ema_hora` datetime NOT NULL COMMENT 'Hora de envío de email',
  `ema_para` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Destinatarios del Email',
  `ema_concopia` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Destinatarios con copia del Email',
  `ema_concopiaoculta` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Destinatarios con copia oculta del Email',
  `ema_ip` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'IP de dónde se origina el email',
  `ema_estado` enum('BORRADOR','ENVIADO') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Estado de envío del email',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `experiencias`
--

CREATE TABLE `experiencias` (
  `exp_id` int(10) UNSIGNED NOT NULL COMMENT 'ID de la experiencia',
  `exp_cargo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Cargo de la experiencia',
  `exp_empresa` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Empresa de la experiencia',
  `exp_fecha_inicio` date DEFAULT NULL COMMENT 'Fecha de inicio de la experiencia',
  `exp_fecha_fin` date DEFAULT NULL COMMENT 'Fecha de fin de la experiencia',
  `exp_descripcion` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'Descripción de la experiencia',
  `exp_logros` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'Logros alcanzados en la experiencia',
  `exp_publicada` enum('SI','NO') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Experiencia publicada',
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
  `for_institucion` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre de la institución',
  `for_grado_titulo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Grado o título obtenido',
  `for_fecha_inicio` date DEFAULT NULL COMMENT 'Fecha de inicio',
  `for_fecha_fin` date DEFAULT NULL COMMENT 'Fecha de finalización',
  `for_logros_principales` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'Logros principales',
  `for_tipo_logro` enum('Enseñanza','Licenciatura','Maestría','Doctorado','Diplomado','Certificación','Curso') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tipo de logro',
  `for_categoria` enum('Curso','Certificación','Formación') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Corresponde a la categoría de la formación',
  `for_publicada` enum('SI','NO') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Indicador de publicación (SI/NO)',
  `for_codigo_validacion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Código de validación',
  `for_certificado` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ruta del archivo del certificado',
  `for_mostrar_certificado` enum('SI','NO') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NO' COMMENT 'Indica si se debe mostrar el certificado',
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
  `gal_tipo_registro` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tipo de registro asociado a la galería',
  `gal_id_registro` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del registro asociado a la galería',
  `gal_descripcion` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'Descripción de la galería',
  `gal_estado` enum('publicado','borrador') COLLATE utf8mb4_unicode_ci DEFAULT 'borrador' COMMENT 'Estado de la galería (publicado/borrador)',
  `gal_titulo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Título de la galería',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habilidades`
--

CREATE TABLE `habilidades` (
  `hab_id` int(10) UNSIGNED NOT NULL COMMENT 'ID de la habilidad',
  `hab_nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre de la habilidad',
  `hab_nivel` int(3) UNSIGNED NOT NULL COMMENT 'Nivel de la habilidad',
  `hab_publicada` enum('SI','NO') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Estado de Publicación',
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
  `her_nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre de la herramienta',
  `her_nivel` int(3) UNSIGNED NOT NULL COMMENT 'Nivel de la herramienta',
  `her_publicada` enum('SI','NO') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Estado de Publicación',
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
  `img_ruta` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ruta de la imagen en el sistema de archivos o URL',
  `img_descripcion` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'Descripción de la imagen',
  `img_estado` enum('publicado','borrador') COLLATE utf8mb4_unicode_ci DEFAULT 'publicado' COMMENT 'Estado de la imagen (publicado/borrador)',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `layouts`
--

CREATE TABLE `layouts` (
  `lay_id` int(10) UNSIGNED NOT NULL,
  `lay_nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del layout',
  `lay_ruta_imagenes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ruta de las imágenes',
  `lay_estado` enum('activo','inactivo') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Estado del layout (activo/inactivo)',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabla para almacenar información sobre los layouts disponibles en la aplicación.';

--
-- Volcado de datos para la tabla `layouts`
--

INSERT INTO `layouts` (`lay_id`, `lay_nombre`, `lay_ruta_imagenes`, `lay_estado`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'Personal', 'PERSONAL', 'activo', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lecciones`
--

CREATE TABLE `lecciones` (
  `lec_id` int(10) UNSIGNED NOT NULL COMMENT 'ID único de la lección',
  `lec_titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Título de la lección',
  `lec_contenido` longtext COLLATE utf8mb4_unicode_ci COMMENT 'Contenido de la lección',
  `lec_tipo` enum('texto','video','ejercicio','codigo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'texto' COMMENT 'Tipo de contenido',
  `lec_orden` int(10) UNSIGNED DEFAULT '0' COMMENT 'Orden dentro del módulo',
  `lec_estado` enum('borrador','publicado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'borrador' COMMENT 'Estado de la lección',
  `lec_slug` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Slug de la lección',
  `lec_imagen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Imagen destacada de la lección',
  `lec_mod_id` int(10) UNSIGNED NOT NULL COMMENT 'ID del módulo al que pertenece la lección',
  `lec_icono` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ícono representativo de la lección',
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
  `med_nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del medio',
  `med_ruta` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ruta del medio en el servidor',
  `med_descripcion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Descripción del medio',
  `med_entidad` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Entidad asociada al medio',
  `med_registro` int(11) DEFAULT NULL COMMENT 'ID del registro asociado (p.ej. pag_id, art_id, etc.)',
  `med_orden` int(11) NOT NULL DEFAULT '0' COMMENT 'Correlativo (MAX+1) por med_entidad,med_tipo,med_registro',
  `med_tipo` enum('entidad','site','tinymce') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'entidad' COMMENT 'Tipo de uso del medio (entidad=contenido, site=global, tinymce=WYSIWYG)',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabla para gestionar medios limitados (imágenes, videos, archivos, etc.) en la aplicación.';

--
-- Volcado de datos para la tabla `media`
--

INSERT INTO `media` (`med_id`, `med_nombre`, `med_ruta`, `med_descripcion`, `med_entidad`, `med_registro`, `med_orden`, `med_tipo`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'logo_capsula', 'default/site/logo_capsula.png', 'Logo Oficial de Cápsula Tech', '', NULL, 0, 'site', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
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
  `men_nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del menú',
  `men_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL del menú',
  `men_etiqueta` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Etiqueta del menú',
  `men_mostrar` enum('Si','No') COLLATE utf8mb4_unicode_ci DEFAULT 'Si' COMMENT 'Indica si el menú debe mostrarse o no',
  `men_nivel` enum('nivel_1','nivel_2') COLLATE utf8mb4_unicode_ci DEFAULT 'nivel_1' COMMENT 'Nivel del menú (nivel_1 para el menú principal, nivel_2 para submenús)',
  `men_link_options` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Opciones adicionales del enlace del menú (por ejemplo, estilos)',
  `men_target` enum('_blank','_self','_parent','_top') COLLATE utf8mb4_unicode_ci DEFAULT '_self' COMMENT 'Atributo target del enlace del menú',
  `men_rol_id` int(10) NOT NULL COMMENT 'ID del rol asociado al menú (clave externa que referencia la tabla de roles)',
  `men_padre_id` int(10) UNSIGNED DEFAULT NULL,
  `men_posicion` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Posición del menú dentro de su nivel 1',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro',
  `men_icono` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bi-house|#223142' COMMENT 'Ícono Bootstrap + color (ej: bi-house|#1abc9c)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ID del menú padre para las opciones de nivel 2';

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`men_id`, `men_nombre`, `men_url`, `men_etiqueta`, `men_mostrar`, `men_nivel`, `men_link_options`, `men_target`, `men_rol_id`, `men_padre_id`, `men_posicion`, `created_at`, `updated_at`, `created_by`, `updated_by`, `men_icono`) VALUES
(1, 'Index', 'site/index', 'Inicio', 'Si', 'nivel_1', '', '_self', 3, NULL, 1, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-house-door|#1bc3d4'),
(2, 'Contenido de mi sitio web', '', 'Contenido de mi sitio web', 'Si', 'nivel_1', '', '_self', 3, NULL, 2, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-window|#2ecc71'),
(3, 'Clientes', 'cliente/index', 'Clientes', 'Si', 'nivel_2', '', '_self', 3, 2, 5, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-people|#2980b9'),
(4, 'Mi Ficha personal', '', '', 'Si', 'nivel_1', '', '_self', 3, NULL, 15, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-person-badge|#d35400'),
(5, 'Trabajadores', 'trabajador/index', 'Ver mis Trabajadores', 'Si', 'nivel_2', '', '_self', 3, 48, 32, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-person-lines-fill|#f39c12'),
(6, 'Portafolio de Proyectos', 'proyecto/index', 'Portafolio de Proyectos', 'Si', 'nivel_2', '', '_self', 3, 48, 33, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-kanban|#16a085'),
(7, 'Configurar sitio web', '', '', 'Si', 'nivel_1', '', '_self', 3, NULL, 20, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-sliders|#8e44ad'),
(8, 'Administración Root', '', '', 'Si', 'nivel_1', '', '_self', 3, NULL, 36, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-shield-lock|#e67e22'),
(9, 'Experiencias', 'experiencia/index', '', 'Si', 'nivel_2', '', '_self', 3, 2, 6, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-briefcase|#c0392b'),
(10, 'Herramientas', 'herramienta/index', '', 'Si', 'nivel_2', '', '_self', 3, 2, 7, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-tools|#34495e'),
(11, 'Habilidades', 'habilidad/index', '', 'Si', 'nivel_2', '', '_self', 3, 2, 8, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-star|#f1c40f'),
(12, 'Formación Académica', 'formacion/index', '', 'Si', 'nivel_2', '', '_self', 3, 2, 9, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-mortarboard|#9b59b6'),
(13, 'Servicios', 'servicio/index', '', 'Si', 'nivel_2', '', '_self', 3, 2, 10, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-gear|#8e44ad'),
(14, 'Redes Sociales', 'red/index', '', 'Si', 'nivel_2', '', '_self', 3, 2, 11, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-share|#2980b9'),
(15, 'Correos electrónicos', 'correo/index', '', 'Si', 'nivel_2', '', '_self', 3, 2, 12, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-envelope|#e67e22'),
(16, 'Páginas', 'pagina/index', '', 'Si', 'nivel_2', '', '_self', 3, 2, 3, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-person-workspace|#223142'),
(17, 'Ver mi Perfil', 'perfil/profile', '', 'Si', 'nivel_2', '', '_self', 3, 4, 16, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-file-earmark-person|#e67e22'),
(18, 'Visualizar mi sitio', '../../sitio/web', '', 'Si', 'nivel_2', '', '_blank', 3, 37, 49, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-globe|#16a085'),
(19, 'Antecedentes Generales', 'biografia/ficha', '', 'No', 'nivel_2', '', '_self', 3, 45, 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-house|#223142'),
(20, 'Visualizar mi CV', 'curriculum/visualizar', '', 'Si', 'nivel_2', '', '_self', 3, 4, 17, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-file-earmark-person|#e67e22'),
(21, 'Visualizar mi sitio', '../../sitio/web', '', 'No', 'nivel_2', '', '_blank', 3, 45, 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-house|#223142'),
(22, 'Fotografias del Sitio', 'media/index', '', 'Si', 'nivel_2', '', '_self', 3, 7, 23, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-images|#27ae60'),
(25, 'Newsletter', 'newsletter/index', 'Suscritos al Newsletter', 'Si', 'nivel_2', '', '_self', 3, 2, 13, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-send|#1bc3d4'),
(26, 'Colores del Sitio', 'color/index', 'Colores del Sitio', 'Si', 'nivel_2', '', '_self', 3, 7, 26, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-palette|#bb4d1e'),
(27, 'Modalidad de las Experiencias', 'modalidad/index', '', 'Si', 'nivel_2', '', '_self', 3, 7, 27, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-collection|#6f42c1'),
(28, 'Categorías de Servicios', 'categoria-servicio/index', '', 'Si', 'nivel_2', '', '_self', 3, 7, 28, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-tags|#16a085'),
(29, 'Asuntos de Formularios de contacto', 'asunto/index', '', 'Si', 'nivel_2', '', '_self', 3, 7, 29, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-envelope-paper|#c0392b'),
(30, 'Roles de Seguridad', 'rol/index', '', 'Si', 'nivel_2', '', '_self', 1, 8, 39, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-person-check|#e67e22'),
(31, 'Usuarios del Sistema', 'user/index', '', 'Si', 'nivel_2', '', '_self', 1, 8, 40, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-person-bounding-box|#2980b9'),
(32, 'Yii Crud', '/gii', '', 'Si', 'nivel_2', '', '_blank', 3, 8, 41, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-braces|#7f8c8d'),
(34, 'Estructura de Tablas', 'root/tablas', '', 'Si', 'nivel_2', '', '_self', 3, 8, 43, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-table|#6c757d'),
(35, 'Consultas SQL', 'root/consultasql', '', 'Si', 'nivel_2', '', '_self', 1, 8, 44, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-search|#bb4d1e'),
(36, 'Administrar Menú de Usuarios', 'menu/index', '', 'Si', 'nivel_2', '', '_self', 1, 8, 45, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-menu-app|#1abc9c'),
(37, 'Links Externos', '', '', 'Si', 'nivel_1', '', '_self', 3, NULL, 35, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-link-45deg|#27ae60'),
(38, 'Layout', 'layout/index', 'Layout del sitio', 'Si', 'nivel_2', '', '_self', 1, 8, 46, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-layout-text-window|#c0392b'),
(39, 'Tema del sitio', 'layout/tema', '', 'Si', 'nivel_2', '', '_self', 3, 2, 14, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-droplet|#2980b9'),
(40, 'Artículos', 'articulo/index', '', 'Si', 'nivel_2', '', '_self', 3, 2, 4, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-newspaper|#e67e22'),
(41, 'Categorías de Artículos', 'categoria-articulo/index', '', 'Si', 'nivel_2', '', '_self', 3, 7, 30, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-tags|#16a085'),
(42, 'E-learning', '', 'E-learning', 'Si', 'nivel_1', '', '_self', 3, NULL, 34, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-mortarboard|#9b59b6'),
(43, 'Cursos', 'curso/index', 'Cursos', 'Si', 'nivel_2', '', '_self', 3, 42, 50, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-journal-bookmark|#9b59b6'),
(44, 'Módulos', 'modulo/index', 'Módulos', 'Si', 'nivel_2', '', '_self', 3, 42, 51, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-collection-play|#1bc3d4'),
(45, 'Lecciones', 'leccion/index', 'Lecciones', 'Si', 'nivel_2', '', '_self', 3, 42, 52, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-book|#2980b9'),
(46, 'Recursos', 'recurso/index', 'Recursos', 'Si', 'nivel_2', '', '_self', 3, 42, 53, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-file-earmark-zip|#34495e'),
(47, 'Administrar mi CV', 'curriculum/administrar', 'Administrar mi CV', 'Si', 'nivel_2', '', '_self', 3, 4, 18, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-clipboard-data|#223142'),
(48, 'Mi Empresa', '', '', 'Si', 'nivel_1', '', '_self', 3, NULL, 31, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-building|#16a085'),
(49, 'Testimonios', 'testimonio/index', '', 'Si', 'nivel_2', '', '_self', 3, 4, 19, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-chat-quote|#16a085'),
(50, 'Status del Sistema', 'root/status', '', 'Si', 'nivel_2', '', '_self', 3, 8, 47, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-activity|#e67e22'),
(51, 'Colores del CMS', 'opcion/cms', '', 'Si', 'nivel_2', '', '_self', 3, 8, 48, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-palette2|#bb4d1e'),
(52, 'API Explorer', 'api-explorer/index', '', 'Si', 'nivel_2', '', '_self', 3, 8, 38, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-cloud-check|#2980b9'),
(53, 'Fotografías de TinyMCE', 'media/tinymce', '', 'Si', 'nivel_2', '', '_self', 3, 7, 22, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-images|#27ae60'),
(54, 'Opciones del sitio', 'opcion/index', '', 'Si', 'nivel_2', '', '_self', 3, 7, 21, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-tools|#bb4d1e'),
(55, 'Categorías de Opciones', 'categoria-opcion/index', '', 'Si', 'nivel_2', '', '_self', 1, 8, 37, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-tag|#e67e22'),
(56, 'Configurar Actividades', 'actividad/configurar', '', 'Si', 'nivel_2', '', '_self', 3, 8, 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1, 'bi-activity|#ec31e8');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modalidad`
--

CREATE TABLE `modalidad` (
  `mod_id` int(10) UNSIGNED NOT NULL COMMENT 'Identificador único de modalidad',
  `mod_nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre de la modalidad de trabajo',
  `mod_publicado` enum('SI','NO') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Indica si la modalidad está publicada',
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
-- Estructura de tabla para la tabla `modulos`
--

CREATE TABLE `modulos` (
  `mod_id` int(10) UNSIGNED NOT NULL COMMENT 'ID único del módulo',
  `mod_titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Título del módulo',
  `mod_descripcion` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'Descripción del módulo',
  `mod_orden` int(10) UNSIGNED DEFAULT '0' COMMENT 'Orden del módulo dentro del curso',
  `mod_estado` enum('borrador','publicado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'borrador' COMMENT 'Estado del módulo',
  `mod_slug` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Slug del módulo',
  `mod_imagen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Imagen de portada del módulo',
  `mod_cur_id` int(10) UNSIGNED NOT NULL COMMENT 'ID del curso al que pertenece el módulo',
  `mod_icono` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ícono representativo del módulo',
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
  `new_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Correo del suscriptor',
  `new_estado` enum('suscrito','dado_de_baja','pendiente','bloqueado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente' COMMENT 'Estado de la suscripción',
  `new_verificado` enum('SI','NO') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NO',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabla para almacenar registros de suscriptores del newsletter';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opciones`
--

CREATE TABLE `opciones` (
  `opc_id` int(10) UNSIGNED NOT NULL COMMENT 'Identificador único de la opción',
  `opc_nombre` varchar(255) NOT NULL COMMENT 'Nombre único y descriptivo de la opción (ej: visual_color_primario)',
  `opc_valor` text COMMENT 'Valor actual de la opción',
  `opc_tipo` enum('string','int','bool','float','json','enum','color') NOT NULL DEFAULT 'string' COMMENT 'Tipo de dato de la opción',
  `opc_cat_id` int(10) UNSIGNED NOT NULL COMMENT 'ID de la categoría funcional a la que pertenece (categorias_opciones.cat_id)',
  `opc_rol_id` int(10) UNSIGNED NOT NULL COMMENT 'ID mínimo de rol requerido para modificar la opción (roles.rol_id)',
  `opc_descripcion` varchar(255) DEFAULT NULL COMMENT 'Descripción breve del propósito y uso de la opción',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de creación del registro',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de última modificación',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó la opción',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que modificó por última vez la opción'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Opciones centralizadas del sistema, referenciadas por categoría y rol mínimo para edición';

--
-- Volcado de datos para la tabla `opciones`
--

INSERT INTO `opciones` (`opc_id`, `opc_nombre`, `opc_valor`, `opc_tipo`, `opc_cat_id`, `opc_rol_id`, `opc_descripcion`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'debug', 'yes', 'bool', 1, 1, 'Activar modo depuración', '2025-05-23 14:01:42', '2025-05-23 19:43:51', 1, 1),
(2, 'salt', 'fsddsfj38343lj0', 'string', 1, 1, 'Salt para funciones criptográficas', '2025-05-23 14:01:42', '2025-05-23 14:01:42', 1, 1),
(3, 'zona_horaria', 'America/Santiago', 'string', 1, 1, 'Zona horaria del sistema', '2025-05-23 14:01:42', '2025-05-23 14:01:42', 1, 1),
(4, 'image_height', '640', 'int', 1, 1, 'Alto por defecto para imágenes', '2025-05-23 14:01:42', '2025-05-23 14:01:42', 1, 1),
(5, 'image_width', '640', 'int', 1, 1, 'Ancho por defecto para imágenes', '2025-05-23 14:01:42', '2025-05-23 20:20:42', 1, 1),
(6, 'meta_author', 'Cápsula Tech', 'string', 2, 2, 'Autor de los metadatos del sitio', '2025-05-23 14:01:42', '2025-05-23 14:01:42', 1, 1),
(7, 'meta_application_name', 'Cápsula Tech', 'string', 2, 2, 'Nombre de la aplicación en metadatos', '2025-05-23 14:01:42', '2025-05-23 14:01:42', 1, 1),
(8, 'meta_description', 'Sitio web del Cliente', 'string', 2, 2, 'Descripción para SEO', '2025-05-23 14:01:42', '2025-05-23 14:01:42', 1, 1),
(9, 'meta_generator', 'CMS Cápsula Tech', 'string', 2, 2, 'Generador de metadatos', '2025-05-23 14:01:42', '2025-05-23 14:01:42', 1, 1),
(10, 'meta_keywords', 'Aqui se deben incluir la descripción del sitio', 'string', 2, 2, 'Palabras clave SEO', '2025-05-23 14:01:42', '2025-05-23 14:01:42', 1, 1),
(11, 'viewport', 'Define cómo se debe ajustar y escalar el contenido...', 'string', 2, 2, 'Meta viewport', '2025-05-23 14:01:42', '2025-05-23 14:01:42', 1, 1),
(12, 'robots', 'Controla el comportamiento de los motores de búsqueda...', 'string', 2, 2, 'Meta robots', '2025-05-23 14:01:42', '2025-05-23 14:01:42', 1, 1),
(13, 'codigo_ga', 'Código de seguimiento de Google Analytics.', 'string', 2, 2, 'Código Google Analytics', '2025-05-23 14:01:42', '2025-05-23 14:01:42', 1, 1),
(14, 'site_name', 'Mi sitio web', 'string', 3, 3, 'Nombre público del sitio', '2025-05-23 14:01:42', '2025-05-31 03:15:44', 1, 1),
(15, 'site_domain', 'tusitio.cl', 'string', 3, 3, 'Dominio principal del sitio', '2025-05-23 14:01:42', '2025-05-23 14:01:42', 1, 1),
(16, 'idioma_sitio', 'es-ES', 'string', 3, 3, 'Idioma principal del sitio', '2025-05-23 14:01:42', '2025-05-23 14:01:42', 1, 1),
(17, 'panel_admin_name', 'Panel de Administración de Cápsula Tech V5.0.0', 'string', 3, 3, 'Nombre del panel de administración', '2025-05-23 14:01:42', '2025-06-13 09:29:36', 1, 1),
(18, 'email_admin', 'contacto@sitiocliente.cl', 'string', 3, 3, 'Correo electrónico principal del administrador', '2025-05-23 14:01:42', '2025-05-23 14:01:42', 1, 1),
(19, 'sitio_online', 'yes', 'bool', 3, 3, '¿El sitio está online?', '2025-05-23 14:01:42', '2025-06-03 00:47:56', 1, 1),
(20, 'contenido_pie', 'Derechos de autor © 2025', 'string', 3, 3, 'Texto de pie de página', '2025-05-23 14:01:42', '2025-05-23 14:01:42', 1, 1),
(21, 'site_emblem', 'Consultor TI y Mentor en Innovación', 'string', 3, 3, 'Emblema o slogan del sitio', '2025-05-23 14:01:42', '2025-05-23 14:01:42', 1, 1),
(22, 'texto_login', 'Si necesitas un nuevo usuario, por favor contáctanos...', 'string', 3, 3, 'Mensaje en el login', '2025-05-23 14:01:42', '2025-05-23 14:01:42', 1, 1),
(23, 'sitio_autor', 'https://www.capsulatech.cl', 'string', 3, 3, 'URL del autor del sitio', '2025-05-23 14:01:42', '2025-05-23 14:01:42', 1, 1),
(24, 'ruta_imagenes', '/panel_admin/images', 'string', 3, 3, 'Ruta base de imágenes en el panel', '2025-05-23 14:01:42', '2025-05-23 14:01:42', 1, 1),
(25, 'cliente_nombre', 'Ejemplo Ltda.', 'string', 3, 3, 'Nombre del cliente para saludos y personalización', '2025-05-23 16:59:11', '2025-05-23 16:59:11', 1, 1),
(26, 'geoapify_api_key', '6dacdd4af3e2491eb482fec8db6d31fe', 'string', 1, 1, 'API Key de Geoapify para clima y geolocalización', '2025-05-23 17:43:15', '2025-05-23 17:43:15', NULL, NULL),
(27, 'latitud_fallback', '-33.45', 'float', 1, 1, 'Latitud de fallback (ej: Santiago)', '2025-05-23 17:43:16', '2025-05-23 17:43:16', NULL, NULL),
(28, 'longitud_fallback', '-70.67', 'float', 1, 1, 'Longitud de fallback (ej: Santiago)', '2025-05-23 17:43:16', '2025-05-23 17:43:16', NULL, NULL),
(29, 'sitio_layout', 'Personal', 'string', 3, 3, 'Nombre del layout activo del sitio', '2025-05-23 20:02:24', '2025-05-23 20:02:24', 1, 1),
(30, 'color_navbar_cms', '#1d1d1d', 'string', 4, 1, 'Color de fondo del navbar del CMS', '1900-01-01 00:00:00', '2025-06-11 17:23:23', 1, 1),
(31, 'color_links_navbar_nivel_1', '#ffffff', 'string', 4, 1, 'Color de los links Nivel_1 Navbar del CMS', '1900-01-01 00:00:00', '2025-05-28 16:01:38', 1, 1),
(32, 'color_links_navbar_nivel_2', '#ffffff', 'string', 4, 1, 'Corresponde al color de los links del menú del CMS', '1900-01-01 00:00:00', '2025-05-28 16:01:40', 1, 1),
(69, 'canonical_url', '', 'string', 2, 2, 'URL canónica de la página', '2025-05-30 23:02:17', '2025-05-30 23:02:17', 1, 1),
(70, 'og_site_name', '', 'string', 2, 2, 'Nombre del sitio para Open Graph', '2025-05-30 23:02:17', '2025-05-30 23:02:17', 1, 1),
(71, 'og_title', '', 'string', 2, 2, 'Título de la página para Open Graph', '2025-05-30 23:02:17', '2025-05-30 23:02:17', 1, 1),
(72, 'og_description', '', 'string', 2, 2, 'Descripción de la página para Open Graph', '2025-05-30 23:02:17', '2025-05-30 23:02:17', 1, 1),
(73, 'og_url', '', 'string', 2, 2, 'URL de la página para Open Graph', '2025-05-30 23:02:17', '2025-05-30 23:02:17', 1, 1),
(74, 'og_type', 'website', 'string', 2, 2, 'Tipo de contenido Open Graph (p.ej. website, article)', '2025-05-30 23:02:17', '2025-05-30 23:02:17', 1, 1),
(75, 'og_image', '', 'string', 2, 2, 'URL de la imagen para Open Graph', '2025-05-30 23:02:17', '2025-05-30 23:02:17', 1, 1),
(76, 'twitter_card', 'summary_large_image', 'string', 2, 2, 'Tipo de tarjeta de Twitter (summary, summary_large_image)', '2025-05-30 23:02:17', '2025-05-30 23:02:17', 1, 1),
(77, 'twitter_site', '', 'string', 2, 2, 'Cuenta de Twitter del sitio (p.ej. @MiSitio)', '2025-05-30 23:02:17', '2025-05-30 23:02:17', 1, 1),
(78, 'twitter_title', '', 'string', 2, 2, 'Título para Twitter Card', '2025-05-30 23:02:17', '2025-05-30 23:02:17', 1, 1),
(79, 'twitter_description', '', 'string', 2, 2, 'Descripción para Twitter Card', '2025-05-30 23:02:17', '2025-05-30 23:02:17', 1, 1),
(80, 'twitter_image', '', 'string', 2, 2, 'URL de la imagen para Twitter Card', '2025-05-30 23:02:17', '2025-05-30 23:02:17', 1, 1),
(81, 'theme_color', '', 'color', 2, 2, 'Color principal del tema (meta theme-color)', '2025-05-30 23:02:17', '2025-05-30 23:02:17', 1, 1),
(82, 'apple_mobile_web_app_capable', 'yes', 'bool', 2, 2, 'Permitir modo web-app en iOS (apple-mobile-web-app-capable)', '2025-05-30 23:02:17', '2025-05-30 23:02:17', 1, 1),
(83, 'apple_mobile_web_app_title', '', 'string', 2, 2, 'Título en pantalla completa en iOS (apple-mobile-web-app-title)', '2025-05-30 23:02:17', '2025-05-30 23:02:17', 1, 1),
(84, 'apple_mobile_web_app_status_bar_style', 'default', 'string', 2, 2, 'Estilo de status bar en iOS (apple-mobile-web-app-status-bar-style)', '2025-05-30 23:02:17', '2025-05-30 23:02:17', 1, 1),
(85, 'manifest_url', '/manifest.json', 'string', 2, 2, 'Ruta al manifiesto PWA (manifest.json)', '2025-05-30 23:02:17', '2025-05-30 23:02:17', 1, 1),
(86, 'json_ld', '', 'json', 2, 2, 'JSON-LD de esquema (Schema.org) para organización', '2025-05-30 23:02:17', '2025-05-30 23:02:17', 1, 1),
(87, 'api_secret_token', 'ABCabc123', 'string', 1, 1, 'Token secreto para leer desde la API', '2025-06-03 13:13:21', '2025-06-03 13:13:21', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paginas`
--

CREATE TABLE `paginas` (
  `pag_id` int(10) UNSIGNED NOT NULL COMMENT 'ID único de la página',
  `pag_titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Título de la página (único)',
  `pag_contenido_antes` longtext COLLATE utf8mb4_unicode_ci COMMENT 'Contenido HTML de la página',
  `pag_contenido_despues` longtext COLLATE utf8mb4_unicode_ci COMMENT 'Contenido HTML después de la página ',
  `pag_fuente_contenido` enum('usar_plantilla','editar_directo') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Indica si se carga desde plantilla o se edita código directo',
  `pag_plantilla` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre de archivo de plantilla en Views (solo nombre, con .php)',
  `pag_contenido_programador` longtext COLLATE utf8mb4_unicode_ci COMMENT 'Bloque de código editable por un programador',
  `pag_css_programador` text COLLATE utf8mb4_unicode_ci COMMENT 'CSS exclusivo para esta página',
  `pag_slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Slug de la página (único)',
  `pag_estado` enum('borrador','publicado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'borrador' COMMENT 'Estado de la página',
  `pag_autor_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del autor de la página',
  `pag_mostrar_menu` enum('SI','NO') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '¿Enlace en menú principal?',
  `pag_posicion` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Posición de la página en el menú',
  `pag_label` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Label del hipervínculo de la Página',
  `pag_modo_contenido` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'autoadministrable' COMMENT 'Modo de edición de la Página',
  `pag_icono` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ícono de la Página',
  `pag_mostrar_menu_secundario` enum('SI','NO') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '¿Enlace en menú secundario?',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabla para almacenar las páginas del sitio web.';

--
-- Volcado de datos para la tabla `paginas`
--

INSERT INTO `paginas` (`pag_id`, `pag_titulo`, `pag_contenido_antes`, `pag_contenido_despues`, `pag_fuente_contenido`, `pag_plantilla`, `pag_contenido_programador`, `pag_css_programador`, `pag_slug`, `pag_estado`, `pag_autor_id`, `pag_mostrar_menu`, `pag_posicion`, `pag_label`, `pag_modo_contenido`, `pag_icono`, `pag_mostrar_menu_secundario`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'Inicio', '', '', 'editar_directo', NULL, '\r\n    <header class=\"hero\">\r\n        <div class=\"hero__overlay\"></div>\r\n        <div class=\"hero__content\">\r\n            <h1 class=\"hero__title\">Potencia tu Contenido, Simplifica tu Gestión</h1>\r\n            <p class=\"hero__subtitle\">Cápsula Tech: Tu CMS modular, potente y listo para el futuro.</p>\r\n            <a href=\"#features\" class=\"button button--primary\">Explora nuestras soluciones</a>\r\n        </div>\r\n    </header>\r\n\r\n    <section id=\"about\" class=\"section about\">\r\n        <div class=\"container\">\r\n            <h2 class=\"section__heading\">¿Quiénes Somos?</h2>\r\n            <div class=\"about__wrapper\">\r\n                <div class=\"about__text-content\">\r\n                    <p class=\"about__text\">\r\n                        En **Cápsula Tech**, creemos en la **libertad creativa** y la **eficiencia operativa**.\r\n                        Diseñamos soluciones de gestión de contenido que combinan **flexibilidad** con una **simplicidad** inigualable.\r\n                        Nuestro CMS te da el control total sobre cada detalle de tu sitio web o aplicación, permitiéndote\r\n                        realizar cambios y actualizaciones al instante, sin necesidad de despliegues de código complejos.\r\n                    </p>\r\n                    <p class=\"about__text\">\r\n                        Olvídate de las limitaciones y prepárate para **escalar tu presencia digital** con una herramienta que se adapta a tus necesidades.\r\n                    </p>\r\n                </div>\r\n            </div>\r\n        </div>\r\n    </section>\r\n\r\n    <section id=\"features\" class=\"section features\">\r\n        <div class=\"container\">\r\n            <h2 class=\"section__heading\">Características Clave</h2>\r\n            <div class=\"features__grid\">\r\n                <div class=\"feature-card\">\r\n                    <i class=\"fas fa-bars feature-card__icon\"></i>\r\n                    <h3 class=\"feature-card__title\">Menús Inteligentes y Dinámicos</h3>\r\n                    <p class=\"feature-card__desc\">\r\n                        Configura y actualiza la navegación de tu sitio directamente desde nuestra API, y observa cómo los cambios se reflejan al instante en cualquier frontend.\r\n                    </p>\r\n                </div>\r\n                <div class=\"feature-card\">\r\n                    <i class=\"fas fa-code feature-card__icon\"></i>\r\n                    <h3 class=\"feature-card__title\">Editor Visual Low-Code</h3>\r\n                    <p class=\"feature-card__desc\">\r\n                        Inyecta elementos HTML, estilos CSS y lógica ligera directamente desde nuestro intuitivo panel de administración, sin escribir una sola línea de código complejo.\r\n                    </p>\r\n                </div>\r\n                <div class=\"feature-card\">\r\n                    <i class=\"fas fa-server feature-card__icon\"></i>\r\n                    <h3 class=\"feature-card__title\">Arquitectura Headless Escalable</h3>\r\n                    <p class=\"feature-card__desc\">\r\n                        Distribuye tu contenido a cualquier plataforma (web, móvil, IoT) con nuestra API robusta y optimizada para alto rendimiento, garantizando máxima flexibilidad.\r\n                    </p>\r\n                </div>\r\n                <div class=\"feature-card\">\r\n                    <i class=\"fas fa-shield-alt feature-card__icon\"></i>\r\n                    <h3 class=\"feature-card__title\">Seguridad Robusta y Control de Versiones</h3>\r\n                    <p class=\"feature-card__desc\">\r\n                        Administra permisos detallados para cada usuario y evita errores con nuestro sistema de control de versiones que protege tu contenido de sobrescrituras.\r\n                    </p>\r\n                </div>\r\n                 <div class=\"feature-card\">\r\n                    <i class=\"fas fa-pencil-alt feature-card__icon\"></i>\r\n                    <h3 class=\"feature-card__title\">Componentes Reutilizables</h3>\r\n                    <p class=\"feature-card__desc\">\r\n                        Crea bloques de contenido que puedes usar una y otra vez en diferentes páginas, agilizando el proceso de creación y manteniendo la consistencia.\r\n                    </p>\r\n                </div>\r\n                 <div class=\"feature-card\">\r\n                    <i class=\"fas fa-globe feature-card__icon\"></i>\r\n                    <h3 class=\"feature-card__title\">Soporte Multilingüe Integrado</h3>\r\n                    <p class=\"feature-card__desc\">\r\n                        Expande tu alcance global con facilidad. Nuestro CMS permite gestionar contenido en múltiples idiomas de forma nativa.\r\n                    </p>\r\n                </div>\r\n            </div>\r\n        </div>\r\n    </section>\r\n\r\n    <section class=\"section cta\">\r\n        <div class=\"container\">\r\n            <h2 class=\"cta__heading\">¿Preparado para transformar tu estrategia de contenido?</h2>\r\n            <p class=\"cta__text\">\r\n                Únete a la creciente comunidad de empresas que ya están experimentando la eficiencia y el poder de Cápsula Tech.\r\n            </p>\r\n            <a href=\"/contacto.html\" class=\"button button--secondary\">Agenda una Demostración</a>\r\n        </div>\r\n    </section>', '', 'inicio', 'publicado', 1, 'SI', 1, 'Inicio', 'administrado_programador', 'bi-house|#000000', 'NO', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(2, 'Sobre mí', '<table style=\"border-collapse: collapse; width: 100%;\" border=\"0\"><colgroup><col style=\"width: 35.4779%;\"><col style=\"width: 64.5221%;\"></colgroup>\r\n<tbody>\r\n<tr>\r\n<td>\r\n<p class=\"p1\" style=\"text-align: justify;\">Mar&iacute;a Elena Cort&eacute;s creci&oacute; en un peque&ntilde;o pueblo costero donde las olas romp&iacute;an con suavidad contra la escarpa de acantilados al atardecer. Desde ni&ntilde;a, mostr&oacute; una curiosidad insaciable por los libros que su abuela tra&iacute;a desde la ciudad: antiguos vol&uacute;menes de mitolog&iacute;a, novelas de aventuras y enciclopedias de ciencia. A los diez a&ntilde;os, ya hab&iacute;a escrito su primer cuaderno de cuentos, inspir&aacute;ndose en los pescadores del puerto y en las leyendas de sirenas que relataba su abuelo. Aunque su familia no contaba con muchos recursos, ella aprovechaba cada rayo de luz para leer a la orilla del mar, mientras so&ntilde;aba con recorrer el mundo y descubrir lugares que solo conoc&iacute;a a trav&eacute;s de las p&aacute;ginas.</p>\r\n</td>\r\n<td><img src=\"../../../recursos/uploads/tinymce/IMG-0002.png\" alt=\"\" width=\"337\" height=\"253\"></td>\r\n</tr>\r\n<tr>\r\n<td colspan=\"2\">Cuando cumpli&oacute; diecisiete, Mar&iacute;a Elena recibi&oacute; una beca para estudiar periodismo en la capital. En la universidad, destac&oacute; por su pluma &aacute;gil y su capacidad para encontrar humanidad en las historias m&aacute;s humildes: entrevist&oacute; a ancianos que hablaban de tradiciones olvidadas, escribi&oacute; cr&oacute;nicas sobre mercados callejeros y cubri&oacute; peque&ntilde;os festivales culturales que, de otra forma, habr&iacute;an pasado desapercibidos. Al graduarse, decidi&oacute; regresar brevemente a su pueblo natal para fundar un diario local que combinara reportajes fotogr&aacute;ficos con relatos orales de los habitantes. Con el tiempo, gracias a su empe&ntilde;o y talento, el peri&oacute;dico creci&oacute; hasta convertirse en un referente regional. Aun as&iacute;, cada vez que Mar&iacute;a Elena se siente agotada, vuelve a la escarpa, se sienta en la misma roca donde le&iacute;a de ni&ntilde;a y escribe un nuevo cuento para recordar por qu&eacute; comenz&oacute; a narrar su propia historia.</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>&nbsp;</p>', '', 'editar_directo', '', '', '', 'sobre-mi', 'publicado', 1, 'SI', 2, 'Sobre mí', 'autoadministrable', 'bi-person|#000000', 'NO', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(3, 'Servicios', '<h2>Servicios</h2>', '', 'usar_plantilla', 'view_servicios.php', '', '', 'servicios', 'publicado', 1, 'SI', 3, 'Servicios', 'administrado_programador', 'bi-gear|#000000', 'NO', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(4, 'Portafolios', '<h2>Mis Proyectos</h2>', '', 'usar_plantilla', 'view_proyectos.php', '', '', 'portafolios', 'publicado', 1, 'SI', 4, 'Portafolios', 'administrado_programador', 'bi-kanban|#000000', 'NO', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(5, 'Experiencia', '<h2>Mi Trayectoria</h2>', '', 'usar_plantilla', 'view_experiencias.php', '', '', 'experiencia', 'publicado', 1, 'SI', 5, 'Experiencia', 'administrado_programador', 'bi-briefcase|#000000', 'NO', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(6, 'Artículos', '<h2>Blog & Artículos</h2>', '', 'usar_plantilla', 'view_articulos.php', NULL, NULL, 'articulos', 'publicado', 1, 'SI', 6, 'Artículos', 'autoadministrable', 'bi-newspaper', 'NO', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(7, 'Testimonios', '<h2>Lo que dicen de m&iacute;</h2>', '', 'usar_plantilla', 'view_testimonios.php', '', '', 'testimonios', 'publicado', 1, 'SI', 7, 'Testimonios', 'administrado_programador', 'bi-chat-quote|#000000', 'NO', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(8, 'Contacto', '', '', 'usar_plantilla', 'view_contacto.php', '', '', 'contacto', 'publicado', 1, 'SI', 8, 'Contacto', 'administrado_programador', 'bi-envelope|#000000', 'NO', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(9, 'offline', '', '', 'editar_directo', NULL, '', '', 'offline', 'publicado', 1, 'NO', 9, '', 'autoadministrable', 'bi-house|#000000', 'NO', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(10, 'Wiki CMS', '', '', 'editar_directo', NULL, '\r\n  <div class=\"doc-container\">\r\n    <a id=\"top\"></a>\r\n\r\n    <!-- Menú de navegación -->\r\n    <nav class=\"doc-toc\">\r\n      <h2 class=\"doc-toc__title\">Índice de contenidos</h2>\r\n      <ul class=\"doc-toc__list\">\r\n        <li class=\"doc-toc__item\"><a class=\"doc-toc__link\" href=\"#descripcion\">1. Descripción general</a></li>\r\n        <li class=\"doc-toc__item\"><a class=\"doc-toc__link\" href=\"#arquitectura\">2. Arquitectura del sistema</a></li>\r\n        <li class=\"doc-toc__item\"><a class=\"doc-toc__link\" href=\"#pila\">3. Pila de tecnología</a></li>\r\n        <li class=\"doc-toc__item\"><a class=\"doc-toc__link\" href=\"#caracteristicas\">4. Características clave</a></li>\r\n        <li class=\"doc-toc__item\"><a class=\"doc-toc__link\" href=\"#flujo-contenido\">5. Flujo de contenido</a></li>\r\n        <li class=\"doc-toc__item\"><a class=\"doc-toc__link\" href=\"#puntos-entrada\">6. Puntos de entrada</a></li>\r\n        <li class=\"doc-toc__item\"><a class=\"doc-toc__link\" href=\"#instalacion\">7. Instalación y configuración</a></li>\r\n        <li class=\"doc-toc__item\"><a class=\"doc-toc__link\" href=\"#integracion\">8. Integración y extensibilidad</a></li>\r\n        <li class=\"doc-toc__item\"><a class=\"doc-toc__link\" href=\"#herramientas\">9. Herramientas de desarrollo</a></li>\r\n      </ul>\r\n    </nav>\r\n\r\n    <!-- Sección 1 -->\r\n    <section id=\"descripcion\" class=\"doc-section\">\r\n      <h1 class=\"doc-section__heading\">1. Descripción general de CMS V4</h1>\r\n      <p class=\"doc-section__text\">\r\n        Este documento proporciona una visión completa de CMS V4, un sistema de gestión de contenido\r\n        modular construido sobre el marco Yii2. Cubre la arquitectura del sistema, las características\r\n        clave, la pila de tecnología y los componentes principales para ayudar a los desarrolladores y\r\n        administradores de sistemas a comprender la estructura y las capacidades generales. Para ver los\r\n        procedimientos de instalación detallados, consulte <em>Sistema de instalación</em>. Para obtener\r\n        información específica sobre la administración del backend, consulte <em>Panel de administración</em>.\r\n        Para obtener instrucciones de desarrollo e información sobre extensiones, consulte\r\n        <em>Guía de desarrollo</em>. [oai_citation:0‡RmunozMM_CMS_V4 _ Wiki Profundo.pdf]\r\n      </p>\r\n      <p class=\"doc-section__back\"><a class=\"doc-back-link\" href=\"#top\">↑ Volver arriba</a></p>\r\n    </section>\r\n\r\n    <!-- Sección 2 -->\r\n    <section id=\"arquitectura\" class=\"doc-section\">\r\n      <h1 class=\"doc-section__heading\">2. Arquitectura del sistema</h1>\r\n      <p class=\"doc-section__text\">\r\n        CMS V4 implementa una arquitectura modular con una clara separación entre la entrega de frontend,\r\n        la administración de backend, los flujos de trabajo de instalación y la integración de API externas.\r\n        [oai_citation:1‡RmunozMM_CMS_V4 _ Wiki Profundo.pdf]\r\n      </p>\r\n      <h2 class=\"doc-section__subheading\">2.1 Componentes principales del sistema</h2>\r\n      <ul class=\"doc-section__list\">\r\n        <li>Estructura modular (módulos, widgets, helpers, controladores)</li>\r\n        <li>Gestión de contenidos (Artículos, Servicios, Clientes, Páginas dinámicas)</li>\r\n        <li>Galería multimedia (TinyMCE + directorio <code>recursos/</code>)</li>\r\n        <li>Sistema de auditoría (behaviors y triggers)</li>\r\n        <li>Sistema de widgets reutilizables</li>\r\n        <li>Integración de API REST (<code>web-api.php</code>)</li>\r\n      </ul>\r\n      <p class=\"doc-section__back\"><a class=\"doc-back-link\" href=\"#top\">↑ Volver arriba</a></p>\r\n    </section>\r\n\r\n    <!-- Sección 3 -->\r\n    <section id=\"pila\" class=\"doc-section\">\r\n      <h1 class=\"doc-section__heading\">3. Pila de tecnología</h1>\r\n      <p class=\"doc-section__text\">\r\n        La pila tecnológica de CMS V4 está basada en PHP 8.x y el framework Yii2, apoyada por Composer\r\n        para gestión de dependencias y ActiveRecord para el acceso a datos. En el frontend utiliza HTML5,\r\n        CSS3 (Bootstrap), jQuery y el editor TinyMCE, y ofrece consumo headless a través de aplicaciones\r\n        Vue.js y móviles. [oai_citation:2‡RmunozMM_CMS_V4 _ Wiki Profundo.pdf]\r\n      </p>\r\n      <p class=\"doc-section__back\"><a class=\"doc-back-link\" href=\"#top\">↑ Volver arriba</a></p>\r\n    </section>\r\n\r\n    <!-- Sección 4 -->\r\n    <section id=\"caracteristicas\" class=\"doc-section\">\r\n      <h1 class=\"doc-section__heading\">4. Características y capacidades clave</h1>\r\n      <table class=\"doc-section__table\">\r\n        <thead>\r\n          <tr>\r\n            <th>Feature</th>\r\n            <th>Implementación</th>\r\n            <th>Ruta / Directorio</th>\r\n          </tr>\r\n        </thead>\r\n        <tbody>\r\n          <tr>\r\n            <td>Estructura modular</td>\r\n            <td>Separación por módulos, widgets, ayudantes, controladores</td>\r\n            <td><code>panel-admin/</code></td>\r\n          </tr>\r\n          <tr>\r\n            <td>Gestión de contenidos</td>\r\n            <td>Artículos, servicios, clientes, páginas dinámicas</td>\r\n            <td><code>panel-admin/models/</code></td>\r\n          </tr>\r\n          <tr>\r\n            <td>Galería multimedia</td>\r\n            <td>Gestión de imágenes y archivos con TinyMCE</td>\r\n            <td><code>recursos/</code></td>\r\n          </tr>\r\n          <tr>\r\n            <td>Sistema de auditoría</td>\r\n            <td>Seguimiento estandarizado via triggers y behaviors</td>\r\n            <td>Todos los modelos</td>\r\n          </tr>\r\n          <tr>\r\n            <td>Sistema de widgets</td>\r\n            <td>Componentes de interfaz reutilizables</td>\r\n            <td><code>app/helpers/</code>, <code>app/widgets/</code></td>\r\n          </tr>\r\n          <tr>\r\n            <td>Integración de API</td>\r\n            <td>Puntos de conexión REST para consumo externo</td>\r\n            <td><code>web-api.php</code>, <code>panel-admin/api/</code></td>\r\n          </tr>\r\n        </tbody>\r\n      </table>\r\n      <p class=\"doc-section__back\"><a class=\"doc-back-link\" href=\"#top\">↑ Volver arriba</a></p>\r\n    </section>\r\n\r\n    <!-- Sección 5 -->\r\n    <section id=\"flujo-contenido\" class=\"doc-section\">\r\n      <h1 class=\"doc-section__heading\">5. Flujo de contenido</h1>\r\n      <ul class=\"doc-section__list\">\r\n        <li>Creación (TinyMCE → validación de formulario → ActiveRecord)</li>\r\n        <li>Procesamiento (eventos, behaviors)</li>\r\n        <li>Almacenamiento (filesystem + MySQL)</li>\r\n        <li>Renderizado (HTML frontend o JSON API)</li>\r\n      </ul>\r\n      <p class=\"doc-section__back\"><a class=\"doc-back-link\" href=\"#top\">↑ Volver arriba</a></p>\r\n    </section>\r\n\r\n    <!-- Sección 6 -->\r\n    <section id=\"puntos-entrada\" class=\"doc-section\">\r\n      <h1 class=\"doc-section__heading\">6. Puntos de entrada</h1>\r\n      <ul class=\"doc-section__list\">\r\n        <li><code>index.php</code> (sitio público)</li>\r\n        <li><code>panel-admin/web/index.php</code> (backend)</li>\r\n        <li><code>web-api.php</code> (API REST)</li>\r\n        <li>Comandos CLI (<code>yii &lt;comando&gt;</code>)</li>\r\n      </ul>\r\n      <p class=\"doc-section__back\"><a class=\"doc-back-link\" href=\"#top\">↑ Volver arriba</a></p>\r\n    </section>\r\n\r\n    <!-- Sección 7 -->\r\n    <section id=\"instalacion\" class=\"doc-section\">\r\n      <h1 class=\"doc-section__heading\">7. Instalación y configuración</h1>\r\n      <ol class=\"doc-section__list\">\r\n        <li>Directorio <code>install/</code> y verificación de extensiones</li>\r\n        <li>Configuración de BD y credenciales</li>\r\n        <li>Creación de usuario administrador</li>\r\n        <li>Finalización y test de acceso</li>\r\n      </ol>\r\n      <p class=\"doc-section__back\"><a class=\"doc-back-link\" href=\"#top\">↑ Volver arriba</a></p>\r\n    </section>\r\n\r\n    <!-- Sección 8 -->\r\n    <section id=\"integracion\" class=\"doc-section\">\r\n      <h1 class=\"doc-section__heading\">8. Integración y extensibilidad</h1>\r\n      <h2 class=\"doc-section__subheading\">8.1 API y arquitectura de integraciones externas</h2>\r\n      <p class=\"doc-section__text\">\r\n        CMS V4 soporta arquitecturas modernas exponiendo endpoints REST con autenticación y respuestas JSON,\r\n        consumibles desde Vue.js, aplicaciones móviles o terceros. [oai_citation:3‡RmunozMM_CMS_V4 _ Wiki Profundo.pdf]\r\n      </p>\r\n      <p class=\"doc-section__back\"><a class=\"doc-back-link\" href=\"#top\">↑ Volver arriba</a></p>\r\n    </section>\r\n\r\n    <!-- Sección 9 -->\r\n    <section id=\"herramientas\" class=\"doc-section\">\r\n      <h1 class=\"doc-section__heading\">9. Herramientas de desarrollo</h1>\r\n      <ul class=\"doc-section__list\">\r\n        <li>Consola Yii (<code>yii migrate</code>, <code>yii gii</code>)</li>\r\n        <li>Composer (<code>composer install</code>, <code>composer update</code>)</li>\r\n        <li>Scripts de migración y seeders</li>\r\n        <li>GitHub Actions para CI/CD de tests y despliegue de docs</li>\r\n      </ul>\r\n      <p class=\"doc-section__back\"><a class=\"doc-back-link\" href=\"#top\">↑ Volver arriba</a></p>\r\n    </section>\r\n\r\n  </div>\r\n', '/* Contenedor principal */\r\n.doc-container {\r\n  max-width: 800px;\r\n  margin: 0 auto;\r\n  padding: 1rem;\r\n  font-family: Arial, sans-serif;\r\n  line-height: 1.6;\r\n  color: #333;\r\n  background: #fff;\r\n}\r\n\r\n/* Menú de contenidos (TOC) */\r\n.doc-toc {\r\n  margin-bottom: 1.5rem;\r\n  border: 1px solid #ddd;\r\n  padding: 1rem;\r\n  border-radius: 4px;\r\n  background: #fafafa;\r\n}\r\n.doc-toc__title {\r\n  margin-bottom: 0.75rem;\r\n  color: #2A3F54;\r\n  font-size: 1.25rem;\r\n}\r\n.doc-toc__list {\r\n  list-style: none;\r\n  padding: 0;\r\n  margin: 0;\r\n}\r\n.doc-toc__item {\r\n  margin: 0.25rem 0;\r\n}\r\n.doc-toc__link {\r\n  color: #4596E6;\r\n  text-decoration: none;\r\n  font-weight: 500;\r\n}\r\n.doc-toc__link:hover {\r\n  text-decoration: underline;\r\n}\r\n\r\n/* Secciones de contenido */\r\n.doc-section {\r\n  margin-bottom: 2rem;\r\n}\r\n.doc-section__heading {\r\n  color: #2A3F54;\r\n  margin-top: 1.5rem;\r\n  margin-bottom: 0.5rem;\r\n  font-size: 1.5rem;\r\n}\r\n.doc-section__subheading {\r\n  color: #2A3F54;\r\n  margin-top: 1rem;\r\n  margin-bottom: 0.5rem;\r\n  font-size: 1.25rem;\r\n}\r\n.doc-section__text {\r\n  margin-bottom: 1rem;\r\n}\r\n.doc-section__list {\r\n  margin: 0.5rem 0 1rem 1.5rem;\r\n}\r\n.doc-section__list li {\r\n  margin-bottom: 0.25rem;\r\n}\r\n\r\n/* Tablas */\r\n.doc-section table {\r\n  width: 100%;\r\n  border-collapse: collapse;\r\n  margin: 1rem 0;\r\n}\r\n.doc-section th,\r\n.doc-section td {\r\n  border: 1px solid #ccc;\r\n  padding: 0.5rem;\r\n  text-align: left;\r\n}\r\n.doc-section th {\r\n  background: #f0f0f0;\r\n}\r\n\r\n/* Código inline */\r\n.doc-section code {\r\n  background: #f4f4f4;\r\n  padding: 2px 4px;\r\n  font-family: Consolas, monospace;\r\n  border-radius: 4px;\r\n}\r\n\r\n/* Enlace “volver arriba” */\r\n.doc-section__back {\r\n  text-align: right;\r\n  margin-top: 1rem;\r\n}\r\n.doc-back-link {\r\n  color: #4596E6;\r\n  font-size: 0.9rem;\r\n  text-decoration: none;\r\n}\r\n.doc-back-link:hover {\r\n  text-decoration: underline;\r\n}', 'wiki-cms', 'publicado', 1, 'NO', 10, 'wiki-cms', 'administrado_programador', 'bi-house|#000000', 'SI', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(11, 'Historial CMS V5 Final', '', '', 'editar_directo', '', '  <div class=\"container\">\r\n    <h1>📘 Historial de funcionalidades del CMS – Rogelio Muñoz</h1>\r\n\r\n    <section>\r\n      <h2>🪡 Versión 1.0 – Fundacional (2020–2021)</h2>\r\n      <p>Sistema inicial basado en Yii2 con estructura mínima para gestión de contenido.</p>\r\n      <ul>\r\n        <li>CRUD básico para modelos como Artículo y Usuario.</li>\r\n        <li>Autenticación (login/logout).</li>\r\n        <li>Sistema simple de roles (<code>isAdmin</code>, <code>isEditor</code>).</li>\r\n        <li>Editor de texto enriquecido (TinyMCE v4).</li>\r\n        <li>Layout responsivo con Bootstrap 3.</li>\r\n        <li>Subida de imágenes sin validaciones complejas.</li>\r\n        <li>Slugs e <code>id</code> visibles en URLs.</li>\r\n      </ul>\r\n    </section>\r\n\r\n    <section>\r\n      <h2>🔧 Versión 2.0 – Modularización (2021–2022)</h2>\r\n      <p>Refactor para separación de lógica y reusabilidad.</p>\r\n      <ul>\r\n        <li>Separación por módulos: <code>admin</code>, <code>api</code>, <code>frontend</code>.</li>\r\n        <li>Widgets reutilizables (<code>MainContentWidget</code>, etc.).</li>\r\n        <li>Resize automático en subida de imágenes.</li>\r\n        <li>Integración de galía en TinyMCE.</li>\r\n        <li>Primeros helpers (<code>Helpers::nombreUsuario()</code>).</li>\r\n        <li>Metatags dinámicos y mejora visual responsive.</li>\r\n      </ul>\r\n    </section>\r\n\r\n    <section>\r\n      <h2>🨠 Versión 3.0 – CMS maduro (2023)</h2>\r\n      <ul>\r\n        <li>Menús dinámicos con <code>getOpcionesMenu()</code>.</li>\r\n        <li>Slugs únicos por contenido.</li>\r\n        <li>Behaviors de auditoría y timestamps.</li>\r\n        <li>Subida por ubicación (default, galería, etc.).</li>\r\n        <li>Editor TinyMCEWidget personalizado.</li>\r\n        <li>Vistas tipo blog.</li>\r\n        <li>Auditoría inicial con campos antiguos.</li>\r\n      </ul>\r\n    </section>\r\n\r\n    <section>\r\n      <h2>🚀 Versión 4.XXX – Profesionalización, API y desacoplamiento (2024–2025)</h2>\r\n      <h3>↺ Refactor estructural</h3>\r\n      <ul>\r\n        <li>Estandarización de campos de auditoría.</li>\r\n        <li>Eliminación de nombres antiguos.</li>\r\n        <li>Widget de auditoría para <code>index</code> y <code>view</code>.</li>\r\n        <li>Helpers modularizados.</li>\r\n      </ul>\r\n\r\n      <h3>🧰 API REST</h3>\r\n      <ul>\r\n        <li>Endpoints: perfil, artículos, servicios, clientes.</li>\r\n        <li>Respuestas normalizadas.</li>\r\n        <li>Preparado para frontend en Vue.js.</li>\r\n      </ul>\r\n\r\n      <h3>🎨 TinyMCE avanzado</h3>\r\n      <ul>\r\n        <li>Subida a <code>/recursos/uploads/tinyMCE</code>.</li>\r\n        <li>Galería modal integrada.</li>\r\n        <li>Cropper.js, rotación de imágenes, estilos preservados.</li>\r\n        <li>Editor de bloques personalizados reutilizables.</li>\r\n        <li>Editor embebido de HTML/CSS/PHP con CodeMirror.</li>\r\n        <li>Modo desarrollador activado por vista.</li>\r\n      </ul>\r\n\r\n      <h3>✉️ Formularios</h3>\r\n      <ul>\r\n        <li>Contacto insertando en <code>correos_electronicos</code>.</li>\r\n        <li>Certificados en modal <code>iframe</code>.</li>\r\n        <li>Rutas base dinámicas con helper.</li>\r\n      </ul>\r\n\r\n      <h3>🧪 En proceso</h3>\r\n      <ul>\r\n        <li>Selector visual FontAwesome.</li>\r\n        <li>Contador caracteres/palabras en editor.</li>\r\n        <li>Soporte de tablas desde Excel.</li>\r\n        <li>API Clima (workaround JS por CORS).</li>\r\n      </ul>\r\n    </section>\r\n\r\n    <section class=\"estado\">\r\n      <h2>📍 Estado actual: Versión 4.XXX (actualizado: 2025-05-28)</h2>\r\n      <p>Preparando roadmap para versión 5.0:</p>\r\n      <ul>\r\n        <li>Plantillas dinámicas frontend.</li>\r\n        <li>Composición visual por bloques.</li>\r\n        <li>Mejoras en accesibilidad (WCAG).</li>\r\n        <li>API documentada públicamente.</li>\r\n        <li>Panel de estadísticas para usuarios y contenido.</li>\r\n      </ul>\r\n    </section>\r\n  </div>\r\n  <p>Test</p>', 'body {\r\n  font-family: \'Nunito Sans\', sans-serif;\r\n  line-height: 1.6;\r\n  background: #f9f9f9;\r\n  color: #333;\r\n  margin: 0;\r\n  padding: 0;\r\n}\r\n\r\n.container {\r\n  max-width: 960px;\r\n  margin: 2rem auto;\r\n  padding: 2rem;\r\n  background: #fff;\r\n  box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);\r\n  border-radius: 12px;\r\n}\r\n\r\nh1 {\r\n  font-size: 2.2rem;\r\n  margin-bottom: 1rem;\r\n  color: #2a2a2a;\r\n}\r\n\r\nh2 {\r\n  margin-top: 2rem;\r\n  color: #4596E6;\r\n  font-size: 1.5rem;\r\n  border-bottom: 1px solid #eee;\r\n  padding-bottom: 0.3rem;\r\n}\r\n\r\nh3 {\r\n  margin-top: 1.5rem;\r\n  color: #333;\r\n  font-size: 1.2rem;\r\n  font-weight: 600;\r\n}\r\n\r\nul {\r\n  margin: 0.5rem 0 1.5rem 1.5rem;\r\n}\r\n\r\nul li {\r\n  margin-bottom: 0.4rem;\r\n}\r\n\r\ncode {\r\n  background: #f1f1f1;\r\n  padding: 0.2em 0.4em;\r\n  border-radius: 4px;\r\n  font-family: monospace;\r\n  font-size: 0.95em;\r\n}\r\n\r\n.estado {\r\n  border-top: 2px dashed #ccc;\r\n  margin-top: 3rem;\r\n  padding-top: 1rem;\r\n}\r\n', 'historial-cms-v5-final', 'publicado', 1, 'NO', 11, 'historial-cms', 'administrado_programador', 'bi-house|#000000', 'SI', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil`
--

CREATE TABLE `perfil` (
  `per_id` int(10) UNSIGNED NOT NULL COMMENT 'ID del Perfil',
  `per_tipo` enum('persona','empresa') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tipo de Perfil (Persona / Empresa)',
  `per_nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del Perfil',
  `per_fecha_nacimiento` date DEFAULT NULL COMMENT 'Fecha de Nacimiento (o Fundación)',
  `per_lugar_nacimiento_fundacion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lugar de Nacimiento o Fundación',
  `per_ubicacion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ubicación',
  `per_nacionalidad` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nacionalidad',
  `per_correo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Correo Electrónico',
  `per_telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Teléfono',
  `per_direccion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Dirección',
  `per_linkedin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'LinkedIn',
  `per_sitio_web` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Sitio Web',
  `per_sector` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Sector (Giro)',
  `per_idiomas` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'Idiomas',
  `singleton` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Indicador Único (Singleton)',
  `per_imagen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ruta de la Imagen del Perfil',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `perfil`
--

INSERT INTO `perfil` (`per_id`, `per_tipo`, `per_nombre`, `per_fecha_nacimiento`, `per_lugar_nacimiento_fundacion`, `per_ubicacion`, `per_nacionalidad`, `per_correo`, `per_telefono`, `per_direccion`, `per_linkedin`, `per_sitio_web`, `per_sector`, `per_idiomas`, `singleton`, `per_imagen`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'persona', 'Hola Mundo', NULL, NULL, NULL, 'Chile', 'rmunoz1612@gmail.com', '+56912345678', NULL, NULL, NULL, NULL, NULL, 1, '', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos`
--

CREATE TABLE `proyectos` (
  `pro_id` int(10) UNSIGNED NOT NULL COMMENT 'Identificador único del proyecto',
  `pro_titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Título del proyecto',
  `pro_descripcion` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'Descripción detallada del proyecto',
  `pro_resumen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Resumen del proyecto',
  `pro_slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Slug único para la URL del proyecto',
  `pro_estado` enum('PUBLICADO','BORRADOR') COLLATE utf8mb4_unicode_ci DEFAULT 'BORRADOR' COMMENT 'Estado del proyecto (publicado o en borrador)',
  `pro_destacado` enum('SI','NO') COLLATE utf8mb4_unicode_ci DEFAULT 'NO' COMMENT 'Indica si el proyecto está destacado',
  `pro_imagen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL de la imagen del proyecto',
  `pro_ser_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del tipo de servicio relacionado con el proyecto',
  `pro_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL del proyecto',
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
  `rec_titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Título del recurso',
  `rec_slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Slug del recurso',
  `rec_tipo` enum('video','documento','imagen','enlace') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'documento' COMMENT 'Tipo de recurso (video, documento, imagen, enlace)',
  `rec_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'URL o ruta del recurso',
  `rec_descripcion` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'Descripción del recurso',
  `rec_imagen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Imagen del recurso',
  `rec_estado` enum('activo','inactivo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'activo' COMMENT 'Estado del recurso (activo/inactivo)',
  `rec_lec_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID de la lección asociada',
  `rec_icono` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ícono representativo del recurso',
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
  `red_nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre de la red social',
  `red_enlace` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Enlace asociado a la red social',
  `red_perfil` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Perfil de la red social',
  `red_publicada` enum('SI','NO') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NO' COMMENT 'Indica si la red social está publicada',
  `red_categoria` enum('Redes sociales principales','Redes sociales de mensajería','Plataformas de videoconferencia','Redes sociales profesionales','Redes sociales populares en China','Redes sociales alternativas','Plataformas para eventos y reuniones','Plataformas de streaming de audio','Plataformas de arte y diseño','Plataformas de fotografía','Directorios y reseñas de negocios','Redes sociales para amantes de la lectura','Plataformas de música en streaming','Otras redes sociales') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `red_icono` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `redes_sociales`
--

INSERT INTO `redes_sociales` (`red_id`, `red_nombre`, `red_enlace`, `red_perfil`, `red_publicada`, `red_categoria`, `red_icono`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'Facebook', 'www.facebook.com', NULL, 'SI', 'Redes sociales principales', 'fab fa-facebook|#1877F2', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(2, 'Instagram', 'www.instagram.com', NULL, 'NO', 'Redes sociales principales', 'fab fa-instagram|#E1306C', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(3, 'LinkedIn', 'www.linkedin.com/in', NULL, 'SI', 'Redes sociales principales', 'fab fa-linkedin|#0077B5', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(4, 'Spotify', 'open.spotify.com/', NULL, 'NO', 'Plataformas de streaming de audio', 'fab fa-spotify|#1DB954', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(5, 'Telegram', 'www.telegram.org', NULL, 'NO', 'Redes sociales principales', 'fab fa-telegram|#0088CC', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(6, 'TikTok', 'www.tiktok.com', NULL, 'NO', 'Redes sociales principales', 'fab fa-tiktok|#000000', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(7, 'Twitter', 'twitter.com', NULL, 'NO', 'Redes sociales principales', 'fab fa-twitter|#1DA1F2', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(8, 'WhatsApp', 'wa.me', NULL, 'NO', 'Redes sociales principales', 'fab fa-whatsapp|#25D366', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(9, 'YouTube', 'www.youtube.com', NULL, 'SI', 'Redes sociales principales', 'fab fa-youtube|#FF0000', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1),
(10, 'Zoom', 'www.zoom.us', NULL, 'NO', 'Plataformas de videoconferencia', 'fas fa-video|#2D8CFF', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `rol_id` int(10) UNSIGNED NOT NULL COMMENT 'Identificador único de Roles',
  `rol_nombre` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del Rol',
  `rol_descripcion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Descripción del Rol',
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
  `ser_titulo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Titulo del Servicio',
  `ser_slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Slug del servicio',
  `ser_resumen` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Resumen del Servicio',
  `ser_cuerpo` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'Cuerpo del Servicio',
  `ser_publicado` enum('SI','NO') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT ' 	Estado de publicación del Servicio ',
  `ser_destacado` enum('NO','SI') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '¿Servicio Destacado?',
  `ser_imagen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Imagen del Servicio',
  `ser_icono` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ícono del Servicio',
  `ser_cat_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'Categoría del Servicio',
  `created_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de creación del registro',
  `updated_at` datetime DEFAULT NULL COMMENT 'Fecha y hora de última modificación del registro',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que creó el registro',
  `updated_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID del usuario que actualizó el registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `testimonios`
--

CREATE TABLE `testimonios` (
  `tes_id` int(10) UNSIGNED NOT NULL COMMENT 'ID único del testimonio',
  `tes_nombre` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT 'Nombre de la persona que da el testimonio',
  `tes_cargo` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Cargo o rol de la persona (opcional)',
  `tes_empresa` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Empresa o institución (opcional)',
  `tes_testimonio` text CHARACTER SET utf8 NOT NULL COMMENT 'Contenido del testimonio',
  `tes_imagen` text CHARACTER SET utf8 COMMENT 'URL o nombre de archivo de la imagen del testimonio',
  `tes_orden` int(10) UNSIGNED DEFAULT '0' COMMENT 'Orden de visualización',
  `tes_estado` enum('borrador','publicado') CHARACTER SET utf8 NOT NULL DEFAULT 'borrador' COMMENT 'Estado del testimonio',
  `tes_slug` text CHARACTER SET utf8 COMMENT 'Slug único para acceso o referencia interna',
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
  `tra_nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tra_apellido` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tra_cedula` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tra_fecha_nacimiento` date DEFAULT NULL COMMENT 'Fecha de nacimiento del trabajador',
  `tra_genero` enum('Masculino','Femenino','Otro') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Género del trabajador',
  `tra_puesto` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Puesto de trabajo del trabajador',
  `tra_departamento` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Departamento o área en la que trabaja el trabajador',
  `tra_fecha_contratacion` date DEFAULT NULL COMMENT 'Fecha de contratación del trabajador',
  `tra_salario` decimal(10,2) DEFAULT NULL COMMENT 'Salario del trabajador',
  `tra_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Correo electrónico del trabajador',
  `tra_telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Número de teléfono del trabajador',
  `tra_direccion` text COLLATE utf8mb4_unicode_ci COMMENT 'Dirección del trabajador',
  `tra_foto_perfil` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ruta de la foto de perfil del trabajador',
  `tra_descripcion` text COLLATE utf8mb4_unicode_ci COMMENT 'Descripción o perfil del trabajador',
  `tra_facebook` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Enlace al perfil de Facebook del trabajador',
  `tra_instagram` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Enlace al perfil de Instagram del trabajador',
  `tra_linkedin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Enlace al perfil de LinkedIn del trabajador',
  `tra_tiktok` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Enlace al perfil de TikTok del trabajador',
  `tra_twitter` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Enlace al perfil de Twitter del trabajador',
  `tra_whatsapp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Número de WhatsApp del trabajador',
  `tra_modalidad_contrato` enum('Plazo Fijo','Indefinido','A Demanda') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Indefinido',
  `tra_publicado` enum('SI','NO') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NO',
  `tra_estado` enum('Activo','Inactivo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Activo',
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
  `usu_username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre de Usuario',
  `usu_email` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Email del Usuario',
  `usu_email_verificado` enum('SI','NO') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NO' COMMENT 'Indica si el correo electrónico ha sido verificado',
  `usu_password` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Password del Usuario',
  `usu_authKey` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Llave de autenticación del Usuario',
  `usu_accessToken` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Token de acceso del Usuario',
  `usu_activate` enum('SI','NO') COLLATE utf8mb4_unicode_ci DEFAULT 'NO' COMMENT 'Estado de activación del usuario',
  `usu_imagen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Imagen del Usuario',
  `usu_nombres` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombres del Usuario',
  `usu_apellidos` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Apellido del Usuario',
  `usu_telefono` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Teléfono del Usuario',
  `usu_ubicacion` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Santiago, CL' COMMENT 'Ubicación por defecto del usuario',
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
(1, 'admin', 'admin@capsulatech.cl', 'SI', 'fszRrCnGrRgQA', '8f23d8ca137ee3474f6363fb6f5fbcb9a5bd41e6d0ab3502ebd44b2b7934c9ee1375e176f67ce09ef8c0b41498c4f9b119cb67f9d1ed519b07f374058807b83b98a58e6def69e87ab19b5d57478f4912211f48c1a2c78531b541873fb4dbfe9baaec2f05', 'f7b27f1d8f91285cae154134d6bf8010cde2f203c7d996e4c1bab8a8be9a64ea24d0871df947fbf8a4ac806cc8cb5b60d0bf710b465005af6bd63b3667e625edc212fd46aa38aa780c797a8ec156b30f0ddf4a2803f8e6170a7904ec3f6014cc796bc42c', 'SI', '../../recursos/uploads/users/1.png', 'Super', 'Administrador', '', 'Santiago, CL', 1, 13, '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 1);

-- --------------------------------------------------------

--
-- Estructura para la vista `actividad_reciente`
--
DROP TABLE IF EXISTS `actividad_reciente`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `actividad_reciente`  AS SELECT `unioned`.`tabla` AS `tabla`, `unioned`.`id` AS `id`, `unioned`.`updated_by` AS `updated_by`, `unioned`.`updated_at` AS `updated_at`, `unioned`.`nombre_registro` AS `nombre_registro` FROM (select 'articulos' AS `tabla`,`cms_v5_final`.`articulos`.`art_id` AS `id`,`cms_v5_final`.`articulos`.`updated_by` AS `updated_by`,`cms_v5_final`.`articulos`.`updated_at` AS `updated_at`,`cms_v5_final`.`articulos`.`art_titulo` AS `nombre_registro` from `cms_v5_final`.`articulos` union all select 'paginas' AS `tabla`,`cms_v5_final`.`paginas`.`pag_id` AS `id`,`cms_v5_final`.`paginas`.`updated_by` AS `updated_by`,`cms_v5_final`.`paginas`.`updated_at` AS `updated_at`,`cms_v5_final`.`paginas`.`pag_titulo` AS `nombre_registro` from `cms_v5_final`.`paginas` union all select 'proyectos' AS `tabla`,`cms_v5_final`.`proyectos`.`pro_id` AS `id`,`cms_v5_final`.`proyectos`.`updated_by` AS `updated_by`,`cms_v5_final`.`proyectos`.`updated_at` AS `updated_at`,`cms_v5_final`.`proyectos`.`pro_titulo` AS `nombre_registro` from `cms_v5_final`.`proyectos` union all select 'clientes' AS `tabla`,`cms_v5_final`.`clientes`.`cli_id` AS `id`,`cms_v5_final`.`clientes`.`updated_by` AS `updated_by`,`cms_v5_final`.`clientes`.`updated_at` AS `updated_at`,`cms_v5_final`.`clientes`.`cli_logo` AS `nombre_registro` from `cms_v5_final`.`clientes` union all select 'servicios' AS `tabla`,`cms_v5_final`.`servicios`.`ser_id` AS `id`,`cms_v5_final`.`servicios`.`updated_by` AS `updated_by`,`cms_v5_final`.`servicios`.`updated_at` AS `updated_at`,`cms_v5_final`.`servicios`.`ser_titulo` AS `nombre_registro` from `cms_v5_final`.`servicios`) AS `unioned` ORDER BY `unioned`.`updated_at` DESC LIMIT 0, 100100  ;

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
-- Indices de la tabla `emails`
--
ALTER TABLE `emails`
  ADD PRIMARY KEY (`ema_id`),
  ADD UNIQUE KEY `ema_id_UNIQUE` (`ema_id`);

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
  MODIFY `cor_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID del correo electrónico';

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
-- AUTO_INCREMENT de la tabla `emails`
--
ALTER TABLE `emails`
  MODIFY `ema_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de Emails';

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
  MODIFY `gal_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de la galería';

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
  MODIFY `img_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de la imagen';

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
  MODIFY `men_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del menú', AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT de la tabla `modalidad`
--
ALTER TABLE `modalidad`
  MODIFY `mod_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de modalidad', AUTO_INCREMENT=4;

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
-- AUTO_INCREMENT de la tabla `opciones`
--
ALTER TABLE `opciones`
  MODIFY `opc_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de la opción', AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT de la tabla `paginas`
--
ALTER TABLE `paginas`
  MODIFY `pag_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único de la página', AUTO_INCREMENT=12;

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
  MODIFY `usu_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de Usuario', AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `opciones`
--
ALTER TABLE `opciones`
  ADD CONSTRAINT `fk_opciones_categoria` FOREIGN KEY (`opc_cat_id`) REFERENCES `categorias_opciones` (`cat_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_opciones_rol` FOREIGN KEY (`opc_rol_id`) REFERENCES `roles` (`rol_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `paginas`
--
ALTER TABLE `paginas`
  ADD CONSTRAINT `fk_pag_autor` FOREIGN KEY (`pag_autor_id`) REFERENCES `usuarios` (`usu_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
