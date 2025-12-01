<?php
namespace app\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;
use app\models\Galerias;

/**
 * GaleriaButtonWidget
 *
 * Muestra un botón que dice:
 * - "Crear Galería" si NO existe ya una galería para (controllerId, recordIdParam)
 * - "Administrar Galería" si SÍ existe
 * - Si no hay un ID de registro (registro sin guardar), muestra un botón deshabilitado
 *
 * EJEMPLO DE USO:
 *   echo GaleriaButtonWidget::widget();
 *   // Asume que en la URL se pasa algo como ?pag_id=123
 */
class GaleriaButtonWidget extends \yii\bootstrap5\Widget
{
    public function run()
    {
        // 1. Determinar el controlador actual (p.ej. "pagina")
        $controllerId = Yii::$app->controller->id;

        // 2. Construir el nombre del parámetro de la URL
        //    Ejemplo: si controllerId = "pagina", substr($controllerId, 0, 3) = "pag"
        //    => $idAttribute = "pag_id"
        $idAttribute = strtolower(substr($controllerId, 0, 3)) . '_id';

        // 3. Recuperar el valor de ese parámetro desde la URL
        //    Ej: ?pag_id=123
        $recordIdParam = Yii::$app->request->get($idAttribute);

        // 4. Si NO existe $recordIdParam (registro sin guardar), mostramos botón deshabilitado
        if (empty($recordIdParam)) {
            return '<button class="btn btn-secondary" disabled>Galería (Debe guardar el registro primero)</button>';
        }

        // 5. Buscar si existe una galería para este controlador y su ID
        $galeriaExistente = Galerias::find()
            ->where([
                'gal_tipo_registro' => $controllerId,
                'gal_id_registro'   => $recordIdParam
            ])
            ->one();

        // 6. Armar la URL y texto del botón según exista o no la galería
        if ($galeriaExistente) {
            // Sí existe galería => "Administrar"
            $url = Url::to(['galeria/update', 'gal_id' => $galeriaExistente->gal_id]);
            $textoBoton = 'Administrar Galería';
        } else {
            // No existe => "Crear"
            $url = Url::to(['galeria/create', 'tipo_registro' => $controllerId, 'id' => $recordIdParam]);
            $textoBoton = 'Crear Galería';
        }

        // 7. Retornar el enlace final
        return '<a href="' . $url . '" class="btn btn-primary">' . $textoBoton . '</a>';
    }
}