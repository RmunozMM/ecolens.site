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

// $contenido->media podría no existir
$media = isset($contenido->media) ? (object)$contenido->media : (object)[];

// 🔒 Fallback adicional en layout
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
        // --- 1. TÍTULO Y DESCRIPCIÓN ---
        $pageTitle = trim($this->title ?? '');
        $siteName  = $opciones->site_name ?? Yii::$app->name;
        $fullTitle = ($pageTitle === '' || strcasecmp($pageTitle, $siteName) === 0)
            ? $siteName
            : "$siteName | $pageTitle";

        $metaDescription = trim($opciones->meta_description ?? '');
        if ($metaDescription === '' && isset($contenido->resumen)) {
            $metaDescription = strip_tags((string)$contenido->resumen);
            if (mb_strlen($metaDescription) > 200) {
                $metaDescription = mb_substr($metaDescription, 0, 197) . '…';
            }
        }

        // --- 2. URL CANÓNICA / ABSOLUTA ---
        // Usa siempre URL absoluta real (incluido /sitio/web/...)
        $canonicalUrl = Yii::$app->request->absoluteUrl;

        // --- 3. IMAGEN OG ---
        // Default: banner del tema
        // Ruta: /sitio/web/themes/<theme>/assets/img/og-default.png
        $defaultOgImage = Url::to("@web/themes/$theme/assets/img/og-default.jpeg", true);

        $finalOgImage = $defaultOgImage;

        // Si página define una imagen OG específica, la usamos
        if (isset($media->og_image) && $media->og_image) {
            $img = trim($media->og_image);

            if (strpos($img, 'http://') === 0 || strpos($img, 'https://') === 0) {
                // Ya viene absoluta
                $finalOgImage = $img;
            } else {
                // Ruta relativa del tipo "recursos/..." o "/recursos/..."
                $imgPath = '/' . ltrim($img, '/');
                $finalOgImage = Url::to($imgPath, true);
            }
        }

        // --- 4. MIME DE LA IMAGEN ---
        $ext    = strtolower(pathinfo(parse_url($finalOgImage, PHP_URL_PATH), PATHINFO_EXTENSION));
        $ogMime = ($ext === 'png') ? 'image/png' : 'image/jpeg';

        // (Opcional) fb:app_id desde params, si algún día lo defines
        $fbAppId = Yii::$app->params['fbAppId'] ?? null;
    ?>

    <title><?= Html::encode($fullTitle) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= Html::encode($metaDescription) ?>">
    <meta name="author" content="<?= Html::encode($opciones->meta_author) ?>">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?= Html::encode($siteName) ?>">
    <meta property="og:url" content="<?= Html::encode($canonicalUrl) ?>">
    <meta property="og:title" content="<?= Html::encode($fullTitle) ?>">
    <meta property="og:description" content="<?= Html::encode($metaDescription) ?>">
    <meta property="og:image" content="<?= Html::encode($finalOgImage) ?>">
    <meta property="og:image:secure_url" content="<?= Html::encode($finalOgImage) ?>">
    <meta property="og:image:type" content="<?= $ogMime ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <?php if ($fbAppId): ?>
        <meta property="fb:app_id" content="<?= Html::encode($fbAppId) ?>">
    <?php endif; ?>

    <!-- Schema.org / Twitter -->
    <meta itemprop="name" content="<?= Html::encode($fullTitle) ?>">
    <meta itemprop="image" content="<?= Html::encode($finalOgImage) ?>">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= Html::encode($fullTitle) ?>">
    <meta name="twitter:description" content="<?= Html::encode($metaDescription) ?>">
    <meta name="twitter:image" content="<?= Html::encode($finalOgImage) ?>">

    <link rel="canonical" href="<?= Html::encode($canonicalUrl) ?>">

    <meta name="theme-color" content="#45AD82">
    <link rel="icon" type="image/x-icon" href="<?= "$base/themes/$theme/assets/img/favicon.ico" ?>">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;700&family=Lora:wght@700&display=swap">
    <link rel="stylesheet" href="<?= "$base/themes/$theme/assets/css/main.css" ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
<?php $this->beginBody() ?>

    <?= $this->render("@app/themes/$theme/views/header.php", [
        'opciones' => $opciones,
        'base'     => $base,
        'theme'    => $theme,
        'contenido'=> $contenido,
        'media'    => $media,
    ]) ?>

    <main>
        <?= $content ?>
    </main>

    <?= $this->render("@app/themes/$theme/views/footer.php", [
        'opciones' => $opciones,
        'contenido'=> $contenido,
    ]) ?>

    <script src="<?= "$base/themes/$theme/assets/js/main.js" ?>"></script>

    <div class="share-floating">
        <button id="btn-share-main" class="share-main" type="button" aria-label="Compartir">
            <i class="fas fa-share-alt" aria-hidden="true"></i>
        </button>
        <div id="share-menu" class="share-menu">
            <button class="share-item share-wa" type="button" data-network="whatsapp" aria-label="Compartir en WhatsApp">
                <i class="fab fa-whatsapp" aria-hidden="true"></i>
            </button>
            <button class="share-item share-fb" type="button" data-network="facebook" aria-label="Compartir en Facebook">
                <i class="fab fa-facebook-f" aria-hidden="true"></i>
            </button>
            <button class="share-item share-ig" type="button" data-network="instagram" aria-label="Compartir en Instagram">
                <i class="fab fa-instagram" aria-hidden="true"></i>
            </button>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const btnMain  = document.getElementById('btn-share-main');
        const menu     = document.getElementById('share-menu');
        const items    = menu ? menu.querySelectorAll('.share-item') : [];

        if (!btnMain || !menu) return;

        const getShareData = () => {
            const url   = window.location.href;
            const h1    = document.querySelector('h1');
            const title = h1 ? h1.innerText.trim() : (document.title || 'EcoLens');
            const text  = `Mira esta página de EcoLens: ${title}\n${url}`;
            return { url, title, text };
        };

        btnMain.addEventListener('click', async () => {
            const { url, title, text } = getShareData();
            if (navigator.share) {
                try {
                    await navigator.share({ title, text, url });
                    return;
                } catch (e) {}
            }
            menu.classList.toggle('open');
            btnMain.classList.toggle('open');
        });

        document.addEventListener('click', (ev) => {
            if (!menu.classList.contains('open')) return;
            if (ev.target.closest('.share-floating')) return;
            menu.classList.remove('open');
            btnMain.classList.remove('open');
        });

        items.forEach(btn => {
            btn.addEventListener('click', () => {
                const net = btn.getAttribute('data-network');
                const { url, title, text } = getShareData();

                if (net === 'whatsapp') {
                    const waUrl = `https://api.whatsapp.com/send?text=${encodeURIComponent(text)}`;
                    window.open(waUrl, '_blank');
                } else if (net === 'facebook') {
                    const fbUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
                    window.open(fbUrl, '_blank');
                } else if (net === 'instagram') {
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(text).then(() => {
                            alert('Enlace copiado. Pégalo en tu publicación o historia de Instagram.');
                        }).catch(() => {
                            alert('Copia manualmente este enlace: ' + url);
                        });
                    } else {
                        alert('Copia manualmente este enlace: ' + url);
                    }
                }
                menu.classList.remove('open');
                btnMain.classList.remove('open');
            });
        });
    });
    </script>

    <style>
    .share-floating {
        position: fixed;
        right: 1.5rem;
        bottom: 1.5rem;
        z-index: 999;
    }
    .share-main {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        border: none;
        cursor: pointer;
        background: #0f172a;
        color: #f9fafb;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 18px rgba(0,0,0,0.25);
        transition: transform 0.12s ease-out, box-shadow 0.12s ease-out, background 0.12s ease-out;
    }
    .share-main:hover {
        background: #111827;
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(0,0,0,0.30);
    }
    .share-main.open {
        background: #111827;
    }
    .share-main i {
        font-size: 1.4rem;
    }
    .share-menu {
        position: absolute;
        right: 0;
        bottom: 60px;
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        opacity: 0;
        pointer-events: none;
        transform: translateY(6px);
        transition: opacity 0.12s ease-out, transform 0.12s ease-out;
    }
    .share-menu.open {
        opacity: 1;
        pointer-events: auto;
        transform: translateY(0);
    }
    .share-item {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.25);
        transition: transform 0.12s ease-out, box-shadow 0.12s ease-out, filter 0.12s ease-out;
    }
    .share-item i {
        font-size: 1.2rem;
    }
    .share-item:hover {
        transform: translateY(-1px);
        filter: brightness(1.05);
        box-shadow: 0 7px 18px rgba(0,0,0,0.25);
    }
    .share-wa {
        background: #22c55e;
    }
    .share-fb {
        background: #2563eb;
    }
    .share-ig {
        background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);
    }
    @media (max-width: 480px) {
        .share-floating {
            right: 1rem;
            bottom: 1rem;
        }
        .share-main {
            width: 48px;
            height: 48px;
        }
        .share-menu {
            bottom: 56px;
        }
        .share-item {
            width: 40px;
            height: 40px;
        }
    }
    </style>

<?php $this->endBody() ?>
</body>
</html>
