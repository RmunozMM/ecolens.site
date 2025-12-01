<?php
namespace app\widgets\tinymce\assets;

use yii\web\AssetBundle;

class CropperAsset extends AssetBundle
{
    // como dependes luego de @app/widgets/tinymce/assets en tu TinyMceAsset:
    public $sourcePath = null;

    public $css = [
        // puedes usar el CDN directamente
        'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css',
    ];
    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}