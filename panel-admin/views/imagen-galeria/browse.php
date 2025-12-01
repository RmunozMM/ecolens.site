<?php
/** @var app\models\ImagenesGaleria[] $imagenes */
use yii\helpers\Html;
use app\helpers\LibreriaHelper;


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Galería de Imágenes</title>
    <style>
        /* Estilos para la galería en overlay/lightbox */
        body {
            font-family: Arial, sans-serif;
            background-color: rgba(0, 0, 0, 0.8); /* Fondo oscuro semi-transparente */
            color: #fff;
            margin: 0;
            padding: 20px;
        }
        h3 {
            text-align: center;
            margin-bottom: 20px;
        }
        .gallery-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .thumb {
            background: #333;
            border: 2px solid #444;
            border-radius: 4px;
            margin: 10px;
            padding: 10px;
            text-align: center;
            width: 150px;
        }
        .thumb img {
            max-width: 100%;
            max-height: 100px;
            display: block;
            margin: 0 auto 10px;
        }
        .select-link {
            display: block;
            text-decoration: none;
            color: #00aaff;
            font-weight: bold;
            cursor: pointer;
        }
        .select-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h3>Selecciona una imagen</h3>
    <div class="gallery-container">
        <?php if (!empty($mensaje)): ?>
            <p style="color: red;"><?= Html::encode($mensaje) ?></p>
        <?php endif; ?>

        <?php if (!empty($imagenes)): ?>
            <?php foreach ($imagenes as $imagen): ?>
                <div class="thumb">
                    <?= Html::img(
                        LibreriaHelper::getRecursos(). "uploads/" . $imagen->img_ruta,
                        ['alt' => 'Imagen', 'title' => 'Click para seleccionar']
                    ) ?>
                    <a class="select-link" onclick="selectImage('<?= LibreriaHelper::getRecursos(). "uploads/" . $imagen->img_ruta ?>')">
                        Seleccionar
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <script>
        function selectImage(url) {
            // En Fancybox con iframe, la ventana padre contiene TinyMCE
            if (parent && parent.tinymce && parent.tinymce.activeEditor) {
                parent.tinymce.activeEditor.insertContent('<img src="' + url + '">');
                // Cierra el iframe de Fancybox
                parent.$.fancybox.close();
            } else {
                alert("No se pudo insertar la imagen en el editor.");
            }
        }
    </script>
</body>
</html>