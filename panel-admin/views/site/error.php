<?php

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var Exception$exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <h2><?= Html::encode("Acceso Prohibido") ?></h2>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
       Usted no cuenta con permisos para entrar a esta sección.
    </p>
    <p>
        Póngase en contacto con nosotros si cree que se trata de un error del servidor. Gracias.
    </p>

</div>
