<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\helpers\SitioUtilidades;


/** @var \yii\web\View $this */
/** @var string $content */
/** @var object $opciones */

// 1️⃣ Normalizamos opciones
$rawOpts = $this->params['opciones'] ?? [];
$o = is_object($rawOpts) ? $rawOpts : (object)$rawOpts;

$defaults = [
    'idioma_sitio'     => 'es-ES',
    'site_name'        => Yii::$app->name,
    'meta_author'      => Yii::$app->params['meta_author'] ?? Yii::$app->name,
    'meta_description' => '',
    'viewport'         => 'width=device-width, initial-scale=1',
    'cliente_nombre'   => Yii::$app->name,
];

foreach ($defaults as $k => $v) {
    if (!property_exists($o, $k) || $o->{$k} === null) {
        $o->{$k} = $v;
    }
}

$opciones = $o;
$theme    = Yii::$app->params['themeName'] ?? 'default';
$base     = Yii::$app->request->baseUrl;

// 2️⃣ Cargamos variables de contenido y media
$contenido = Yii::$app->view->params['contenido'] ?? (object)[];
if (empty($contenido) || !property_exists($contenido, 'paginas')) {
    $contenido = $this->params['contenido'] ?? (object)[];
}

// $contenido->media podría no existir (por ejemplo, si la página no lo usa)
$media = isset($contenido->media) ? (object)$contenido->media : (object)[];

// 🔒  Fallback adicional en layout (por si el controlador no corta)
if (isset($contenido->pagina_offline) && is_object($contenido->pagina_offline)) {
    $po = $contenido->pagina_offline;

    $htmlOffline = $po->pag_contenido_programador
        ?? (($po->pag_contenido_antes ?? '') . ($po->pag_contenido_despues ?? ''));

    echo $htmlOffline;
    return;
}


?>
<!DOCTYPE html>
<html lang="<?= Html::encode($opciones->idioma_sitio) ?>">
<head>
    <meta charset="UTF-8">
    <?php
        // Determina título de la página (si está disponible desde $this->title o desde contenido)
        $pageTitle = trim($this->title ?? '');
        $siteName  = $opciones->site_name ?? Yii::$app->name;

        // Si no hay título definido, usa solo el nombre del sitio
        if ($pageTitle === '' || strcasecmp($pageTitle, $siteName) === 0) {
            $fullTitle = $siteName;
        } else {
            // Genera formato "EcoLens | Inicio"
            $fullTitle = "{$siteName} | {$pageTitle}";
        }
        ?>
    <title><?= Html::encode($fullTitle) ?></title>

    <meta name="author" content="<?= Html::encode($opciones->meta_author) ?>">
    <meta name="description" content="<?= Html::encode($opciones->meta_description) ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Colores del tema -->
    <meta name="theme-color" content="#45AD82" />

    <!-- Fuentes -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;700&family=Lora:wght@700&display=swap" rel="stylesheet">

    <!-- CSS principal -->
    <link rel="stylesheet" href="<?= "$base/themes/$theme/assets/css/main.css" ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= "$base/themes/$theme/assets/img/favicon.ico" ?>">
    <link rel="apple-touch-icon" href="<?= "$base/themes/$theme/assets/img/apple-touch-icon.png" ?>">

    <!-- Bootstrap + jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
<?php $this->beginBody() ?>

    <!-- HEADER -->
    <?= $this->render("@app/themes/$theme/views/header.php", [
        'opciones' => $opciones,
        'base'     => $base,
        'theme'    => $theme,
        'contenido'=> $contenido,
        'media'    => $media,
    ]) ?>

    <!-- CONTENIDO -->
    <main>
        <?= $content ?>
    </main>

    <!-- FOOTER -->
    <?= $this->render("@app/themes/$theme/views/footer.php", [
        'opciones' => $opciones,
        'contenido'=> $contenido,
    ]) ?>

    <!-- JS principal -->
    <script src="<?= "$base/themes/$theme/assets/js/main.js" ?>"></script>

<?php $this->endBody() ?>
</body>
</html>