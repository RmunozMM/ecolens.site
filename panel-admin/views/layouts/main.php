<?php
/**
 * Layout principal de la aplicación
 *
 * @var yii\web\View $this
 * @var string       $content
 */

use yii\bootstrap5\Html;
use yii\bootstrap5\Breadcrumbs;
use yii\helpers\Url;
use yii\helpers\Inflector;
use app\assets\AppAsset;
use app\assets\AppCustomAsset;
use app\helpers\LibreriaHelper;
use app\helpers\UsuarioHelper;
use app\models\Menu;
use app\widgets\Alert;
use app\widgets\DatepickerWidget;
use app\widgets\TinyMCEWidget;
use app\widgets\MenuLateralWidget;
use app\widgets\TopNavWidget;
use app\widgets\AccesibilidadWidget;
use app\widgets\FooterWidget;
use app\widgets\MainContentWidget;
use app\helpers\SitioHelper;


$fontSize = UsuarioHelper::obtenerTamanioFuente(Yii::$app->user->identity);


// 1. Registro de assets
AppAsset::register($this);
AppCustomAsset::register($this);

// 2. Obtener datos generales
$menuItems = (new Menu)->getOpcionesMenu();

// 3. Metaetiquetas básicas
$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/img/favicon.ico')]);

$this->beginPage();
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title>
        <?= Yii::$app->params['panel_admin_name'] . " de " . Yii::$app->params['site_name'] . " | " . Html::encode($this->title) ?>
    </title>
    <?php $this->head() ?>
</head>


<?php if (!Yii::$app->user->isGuest): ?>
<body style="font-size: <?= Html::encode($fontSize) ?>px;">
<?php $this->beginBody() ?>

<?php $imagen = UsuarioHelper::obtenerImagen(Yii::$app->user->identity); ?>

<!-- CONTENEDOR PRINCIPAL (SIDEBAR + ZONA DERECHA) -->
<div class="app-wrapper">

    <!-- SIDEBAR -->
    <header id="sidebar" class="sidebar" style="background: <?= Yii::$app->params['color_navbar_cms'] ?>">

        <!-- LOGO -->
        <?php echo SitioHelper::logoHTML(); ?>


        <!-- Usuario -->
        <div class="sidebar-user text-center">
            <img src="<?= $imagen ?>" alt="User" class="sidebar-user-img">
            <div class="sidebar-user-name"><?= Yii::$app->user->identity->usu_username ?></div>
        </div>
        <!-- Menú lateral + accesibilidad -->
        <?= MenuLateralWidget::widget(['items' => $menuItems]) ?>
        <?= AccesibilidadWidget::widget() ?>
    </header>

    <!-- ZONA DERECHA (TopNav + Contenido + Footer) -->
    <div class="right_col_wrapper">
        <?= TopNavWidget::widget(['imagen' => $imagen]) ?>
        <?= MainContentWidget::widget(['content' => $content]) ?>
        <?= FooterWidget::widget() ?>
    </div>

</div>

<?php $this->endBody() ?>
</body>

<?php else: ?>
<!-- VISTA PARA USUARIOS NO AUTENTICADOS -->
<body style="background-image: url('<?= Yii::getAlias('@web/img/login.jpg') ?>');
             background-size: cover;
             background-repeat: no-repeat;
             background-attachment: fixed;">
    <?= $content ?>
</body>
<?php endif; ?>
</html>
<?php $this->endPage() ?>

