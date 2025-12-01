<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\helpers\LibreriaHelper;
use yii\helpers\Url;


/** @var yii\web\View $this */
/** @var app\models\Galerias $model */
/** @var yii\widgets\ActiveForm $form */
/** @var string|null $galTipoRegistro */
/** @var int|null $galIdRegistro */


?>

<div class="galerias-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- Contenedor para el formulario de imágenes -->
    <div id="contenedor-formulario-imagenes"></div>

    <div class="form-group">
        <?= Html::submitButton('Guardar Cambios', ['class' => 'btn btn-success']) ?>
        
        <!-- Enlace para abrir el formulario de imágenes -->
        <?= Html::a('Agregar Imágenes', ['/imagen-galeria/create', 'gal_id' => $model->gal_id], ['class' => 'btn btn-primary']) ?>
    </div>

    <?= $form->field($model, 'gal_titulo')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'gal_estado')->dropDownList([ 'publicado' => 'Publicado', 'borrador' => 'Borrador', ], ['prompt' => '']) ?>

    <?php if($model->gal_id): ?>
        <?= $form->field($model, 'gal_tipo_registro')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'gal_id_registro')->hiddenInput()->label(false) ?>
    <?php else: ?>
        <?= $form->field($model, 'gal_tipo_registro')->textInput(['value'=>$tipo_registro])->label(false) ?>

        <?= $form->field($model, 'gal_id_registro')->textInput(['value'=>$id_registro])->label(false) ?>
    <?php endif; ?>

    <?= $form->field($model, 'gal_descripcion')->textarea(['rows' => 4, 'id' => 'tinyMCE', 'style' => 'height: 450px;']) ?>

    <?php ActiveForm::end(); ?>
</div>


<div class="contenedor_imagenes">
    <p>Imágenes que tiene la galería</p>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($imagenes as $index => $imagen): ?>
            <tr>
                <td>
                    <a href="<?= LibreriaHelper::getRecursos(). "uploads/" . $imagen->img_ruta ?>" 
                        data-fancybox="grupo-imagenes"
                        data-caption="Imagen <?= $index + 1 ?>">
                        <img class="imagen-container fancybox" src="<?= LibreriaHelper::getRecursos(). "uploads/" . $imagen->img_ruta ?>" alt="Imagen <?= $index + 1 ?>">
                    </a>
                </td>

                <td>
                    <!-- Asigna el ID único generado al atributo data-img-id -->
                    <textarea class="form-control descripcion-input" data-img-id="<?= $imagen->img_id ?>" data-edit-url="<?= Yii::$app->urlManager->createUrl(['imagen-galeria/editar-descripcion', 'img_id' => $imagen->img_id]) ?>"><?= Html::encode($imagen->img_descripcion) ?></textarea>

                </td>

                <td style="width:200px;">


                    <a href="<?= Url::to(['imagen-galeria/delete', 'img_id' => $imagen->img_id]) ?>" 
                        class="btn-delete fa-solid fa-trash" 
                        style="color:#c40808;" 
                        title="Eliminar Imagen" 
                        data-confirm="¿Estás seguro de querer eliminar esta imagen?. Esta acción no se puede deshacer">
                        <!-- Tu contenido del enlace, como un ícono de papelera, podría ir aquí -->
                    </a>


                    <?php $url = Yii::$app->urlManager->createUrl(['imagen-galeria/editar-descripcion', 'img_id' => $imagen->img_id]); ?>
                    <!-- Asigna el ID único generado al atributo data-img-id -->
                    <a href="javascript:void(0);" data-url="<?= $url ?>" data-img-id="<?= $imagen->img_id ?>" class="editar-descripcion btn btn-primary">Editar Descripción</a>
                
                    <!-- Elemento para mostrar el mensaje de éxito -->
                    <div class="mensaje-exito" style="color: green; max-width: 300px; display: none;"></div>
                    
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>

<script>
$(document).ready(function() {
    tinymce.init({
        selector: 'textarea.descripcion-input',
        // Resto de tu configuración de TinyMCE...
    });



    $('.editar-descripcion').on('click', function(event) {
    event.preventDefault();

    var descripcionTextarea = $(this).closest('tr').find('.descripcion-input');
    var imgId = descripcionTextarea.data('img-id');
    var nuevaDescripcion = tinymce.get(descripcionTextarea.attr('id')).getContent(); // Obtén el contenido actual del TinyMCE

    var mensajeExito = $(this).closest('tr').find('.mensaje-exito'); // Encuentra el mensaje de éxito dentro de la misma fila

    console.log('Valor de img_id:', imgId);
    console.log('Nueva Descripción:', nuevaDescripcion);

    // Armar la URL para llamar al controlador y pasar los datos
    var url = $(this).data('url') + '&img_descripcion=' + encodeURIComponent(nuevaDescripcion);

    console.log('URL:', url);

    $.ajax({
        url: url,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                // Operación exitosa, mostrar mensaje de éxito
                mensajeExito.text('¡La descripción se ha guardado exitosamente!');
                mensajeExito.show(); // Mostrar el mensaje

                // Desaparecer el mensaje después de 5 segundos (5000 milisegundos)
                setTimeout(function() {
                    mensajeExito.hide(); // Ocultar el mensaje después de 5 segundos
                }, 5000);

                console.log('Operación exitosa');
            } else {
                // Error durante la operación, mostrar el mensaje de error
                console.error('Error al guardar la descripción:', response.error);
            }
        },
        error: function(error) {
            // Manejar errores si ocurren durante la llamada al controlador
            console.error('Error en la llamada al controlador:', error);
        }
    });
});



    $(".fancybox").fancybox({
        loop: true,
        buttons: ["slideShow", "fullScreen", "thumbs", "close"],
        idleTime: false,
        animationEffect: "fade",
        transitionEffect: "slide",
        transitionDuration: 600,
        protect: true,
        hideScrollbar: false
    });
});


</script>

<style>
.contenedor_imagenes{
    padding-top:30px;
}
.imagen-container {
    width: 200px; /* Ancho fijo para el contenedor de cada imagen */
    height: 150px; /* Altura fija para el contenedor de cada imagen */
    overflow: hidden; /* Establece el desbordamiento para mantener la relación de aspecto */
    margin-right: 10px; /* Espaciado horizontal entre las imágenes */
}

.imagen {
    width: 100%; /* Establece el ancho de la imagen al 100% del contenedor */
    height: auto; /* Permite que la altura se ajuste automáticamente para mantener la relación de aspecto */
}

/* Clearfix para manejar el espaciado en línea */
.clearfix::after {
    content: "";
    clear: both;
    display: table;
}

/* Estilos para el contenedor principal */
.imagenes-container {
    display: flex; /* Establece el contenedor principal como un flex container */
    flex-wrap: wrap; /* Permite que las imágenes se envuelvan a la siguiente línea si no hay espacio suficiente */
    justify-content: flex-start; /* Alinea las imágenes al inicio del contenedor principal */
}


</style>