<?php
// app/helpers/SitioHelper.php
namespace app\helpers;

use app\models\Media;
use yii\helpers\Html;
use Yii;

class SitioHelper
{
    public static function logoHTML(): string
    {
        $media = Media::find()
            ->where(['med_tipo' => 'site', 'med_nombre' => 'logo_capsula'])
            ->orderBy(['med_id' => SORT_DESC])
            ->limit(1)
            ->one();

        $baseUrl = str_replace('/panel-admin/web', '', Yii::getAlias('@web'));

        $logoHTML = '';
        if ($media && $media->med_ruta) {
            $url = "{$baseUrl}/recursos/uploads/{$media->med_ruta}";
            $logoHTML = Html::img($url, [
                'alt'     => 'Logo Cápsula Tech',
                'class'   => 'sidebar-logo-img logo-full',
                'style'   => 'max-height: 50px;',
                'loading' => 'lazy'
            ]);
        }

        $textoLogo = Html::tag('span', 'CT', [
            'class' => 'logo-colapsed',
            'style' => 'font-weight: bold; font-size: 20px; color: #fff; display: none;',
        ]);

        return Html::a(
            $logoHTML . $textoLogo,
            Yii::$app->homeUrl,
            ['class' => 'sidebar-logo-link']
        );
    }
}
?>