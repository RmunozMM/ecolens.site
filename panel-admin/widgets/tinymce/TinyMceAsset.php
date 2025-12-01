<?php
namespace app\widgets\tinymce;

use yii\web\AssetBundle;
use app\widgets\tinymce\assets\CropperAsset;

class TinyMceAsset extends AssetBundle
{
    public $sourcePath = '@app/widgets/tinymce';

    public $css = [
        'assets/css/tinymce-logic.css',
        'assets/css/edit-image.css',
    ];

    public $js = [
        // Usa tu clave real aquí:
        'https://cdn.tiny.cloud/1/e50joyninvt5uc0ahirzxkql8ono7tyrxwgo070dpubshnmu/tinymce/8/tinymce.min.js',
        'assets/js/tinymce-logic.js',
        'assets/js/edit-image.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        CropperAsset::class,
    ];
}