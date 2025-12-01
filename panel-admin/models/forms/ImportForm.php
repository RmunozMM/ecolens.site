<?php
namespace app\models\forms;

use yii\base\Model;
use yii\web\UploadedFile;

class ImportForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $uploadFile;

    public function rules()
    {
        return [
            [
                ['uploadFile'],
                'file',
                // Extensiones permitidas
                'extensions' => 'csv, xls, xlsx',
                // Desactiva la comprobación del tipo MIME,
                // para que se valide solo la extensión del archivo.
                'checkExtensionByMimeType' => false,
                // Si quieres forzar un archivo sí o sí, omite skipOnEmpty.
                'skipOnEmpty' => false,
            ],
        ];
    }
}