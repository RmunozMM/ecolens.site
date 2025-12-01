<?php

use yii\helpers\Html;
use app\widgets\SaludoWidget;
use app\widgets\ActividadRecienteWidget;


/**
 * @var yii\web\View $this
 * @var int $cantidadCorreosPendientes
 * @var object $online
 * @var object $debug
 */

$this->title = 'Panel de Inicio';
?>

<div class="atlas-dashboard-header mb-4">
    <div class="atlas-dashboard-header-flex">
        <div class="atlas-dashboard-greeting">
            <h1 class="atlas-greeting-title">
                <span class="atlas-greeting-emoji">👋</span> Hola, <?= Html::encode(Yii::$app->user->identity->usu_username); ?>
            </h1>
            <h2 class="atlas-greeting-welcome">
                Bienvenido al <span class="atlas-greeting-panel"><?= Html::encode(Yii::$app->params['panel_admin_name']) ?></span>
            </h2>
            <div class="atlas-greeting-admin">
                <span class="atlas-greeting-admin-label">Administrando:</span>
                <span class="atlas-greeting-client"><?= Html::encode(Yii::$app->params["cliente_nombre"]); ?></span>
            </div>
            <p class="atlas-greeting-desc">
                Administra tu sitio de forma centralizada, rápida y segura.<br>
                <span class="atlas-greeting-highlight">¡Todo lo importante en un solo lugar!</span>
            </p>
        </div>
        <div class="atlas-dashboard-clima">
            <?= SaludoWidget::widget(); ?>
        </div>
    </div>
</div>

<div class="row g-3 dashboard-grid">
    <!-- Correos -->
    <div class="col-md-4">
        <div class="dashboard-card">
            <h5>Correos Pendientes</h5>
            <p class="dashboard-count"><?= $cantidadCorreosPendientes ?></p>
            <?= Html::a('<i class="fa-solid fa-reply"></i>', ['correo/index'], [
                'class' => 'btn-dashboard toggle-neutral',
                'title' => 'Ir a responder'
            ]) ?>
        </div>
    </div>

    <!-- Estado del sitio -->
    <div class="col-md-4">
        <div class="dashboard-card">
            <h5>Sitio Web</h5>
            <p class="dashboard-status <?= $online->opc_valor === 'yes' ? 'text-success' : 'text-danger' ?>">
                <?= $online->opc_valor === 'yes' ? 'Publicado' : 'No Publicado' ?>
            </p>
            <?= Html::a(
                '<i class="fa-solid fa-globe"></i>',
                ['opcion/publicar', 'id' => $online->opc_id, 'valor' => $online->opc_valor === 'yes' ? 'yes' : 'no'],
                [
                    'class' => 'btn-dashboard ' . ($online->opc_valor === 'yes' ? 'toggle-on' : 'toggle-off'),
                    'title' => 'Publicar / Despublicar'
                ]
            ) ?>
        </div>
    </div>

    <!-- Debug (admin) -->
    <?php if (Yii::$app->user->identity->usu_rol_id == 1): ?>
        <div class="col-md-4">
            <div class="dashboard-card">
                <h5>Modo Debug</h5>
                <p class="dashboard-status <?= $debug->opc_valor === 'yes' ? 'text-success' : 'text-danger' ?>">
                    <?= $debug->opc_valor === 'yes' ? 'Activo' : 'Inactivo' ?>
                </p>
                <?= Html::a(
                    '<i class="fa-solid fa-bug"></i>',
                    ['opcion/debug', 'id' => $debug->opc_id, 'valor' => $debug->opc_valor === 'yes' ? 'no' : 'yes'],
                    [
                        'class' => 'btn-dashboard ' . ($debug->opc_valor === 'yes' ? 'toggle-on' : 'toggle-off'),
                        'title' => 'Activar / Desactivar Debug'
                    ]
                ) ?>
            </div>
        </div>
    <?php endif; ?>

        <!-- Dentro del grid del dashboard -->
    <?php if (Yii::$app->user->identity->usu_rol_id == 1): ?>
    <div class="col-md-4">
        <div class="dashboard-card">
        <h5>Limpiar Caché</h5>
        <p class="dashboard-status text-muted">Forzar recarga de datos</p>
        <?= Html::a('<i class="fa-solid fa-broom"></i>', ['site/clear-cache'], [
            'class' => 'btn-dashboard toggle-neutral',
            'title' => 'Limpiar caché del sistema',
            'data-confirm' => '¿Deseas limpiar la caché del sistema?',
        ]) ?>
        </div>
    </div>
    <?php endif; ?>


</div>

<?= ActividadRecienteWidget::widget(); ?>

<!-- Bloques tip/frase del día -->
<?php
$tips = [
    'Puedes usar el editor TinyMCE para dar formato avanzado a tus páginas.',
    'No olvides revisar la sección de Testimonios: es clave para validar tu propuesta.',
    'El orden de los elementos se puede ajustar con drag & drop si el módulo lo permite.',
];
$tip = $tips[array_rand($tips)];
$frases = [
    'La tecnología es mejor cuando reúne a las personas. – Matt Mullenweg',
    'El software es como el sexo: es mejor cuando es libre. – Linus Torvalds',
    'No te preocupes por fallar, solo tienes que acertar una vez. – Drew Houston',
    'Si no estás avergonzado de tu primera versión, lanzaste demasiado tarde. – Reid Hoffman',
    'Los detalles no son los detalles. Los detalles son el diseño. – Charles Eames',
];
$frase = $frases[array_rand($frases)];
?>
<div class="row mt-4">
    <div class="col-md-6">
        <div class="tip-widget p-3 rounded" style="background: #fefefe; border-left: 5px solid #2c3e50;">
            <strong>💡 Tip del día:</strong> <?= $tip ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="tip-widget p-3 rounded" style="background: #fefefe; border-left: 5px solid #7f8c8d;">
            <strong>🧠 Frase del día:</strong> <em><?= $frase ?></em>
        </div>
    </div>
</div>

<!-- FontAwesome si no lo tienes aún -->
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
      />

<style>
/* Header y clima */
.atlas-dashboard-header {
    background: #fff;
    padding: 32px 36px 26px 36px;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(60,60,60,0.08);
    margin-bottom: 2.5rem;
}
.atlas-dashboard-header-flex {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-start;
    justify-content: space-between;
}
.atlas-dashboard-greeting {
    min-width: 260px;
    max-width: 60%;
}
.atlas-greeting-title {
    font-size: 2.1rem;
    font-weight: 700;
    color: #223142;
    margin-bottom: .3rem;
}
.atlas-greeting-emoji {
    font-size: 2.2rem;
    vertical-align: baseline;
    margin-right: 6px;
}
.atlas-greeting-welcome {
    font-size: 1.2rem;
    font-weight: 600;
    color: #e66a17;
    margin-bottom: 8px;
}
.atlas-greeting-panel {
    color: #e66a17;
}
.atlas-greeting-admin {
    font-size: 1.09rem;
    font-weight: 600;
    color: #31577a;
    margin-bottom: 13px;
}
.atlas-greeting-admin-label {
    color: #0066cc;
    margin-right: 2px;
}
.atlas-greeting-client {
    color: #223142;
}
.atlas-greeting-desc {
    font-size: 1rem;
    color: #8592a6;
}
.atlas-greeting-highlight {
    color: #e66a17;
    font-weight: 600;
    font-size: 1rem;
}
.atlas-dashboard-clima {
    max-width: 380px;
    min-width: 210px;
    flex-shrink: 0;
    margin-left: 24px;
    margin-top: 8px;
}
.atlas-clima-widget {
    background: #f9fafb;
    border-radius: 16px;
    padding: 18px 24px 15px 24px;
    font-size: 1rem;
    color: #223142;
    box-shadow: 0 2px 10px rgba(60,60,60,0.07);
    font-style: italic;
    min-width: 200px;
    max-width: 100%;
    text-align: left;
    line-height: 1.45;
    margin-bottom: 0;
}
/* Cards del dashboard */
.dashboard-grid .dashboard-card {
    height: 160px;
    padding: 20px;
    background-color: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: flex-start;
    transition: box-shadow 0.2s ease;
}
.dashboard-card:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
}
.dashboard-card h5 {
    margin: 0;
    font-size: 16px;
    font-weight: bold;
}
.dashboard-count {
    font-size: 32px;
    font-weight: 700;
    margin: 0;
}
.dashboard-status {
    font-size: 16px;
    font-weight: 600;
    margin: 0;
}
.btn-dashboard {
    font-size: 20px;
    padding: 8px 14px;
    border-radius: 50px;
    color: #fff;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    border: none;
    text-decoration: none;
    transition: transform 0.2s ease;
}
.btn-dashboard:hover {
    transform: scale(1.05);
}
.toggle-on {
    background-color: #186a3b;
}
.toggle-off {
    background-color: #c0392b;
}
.toggle-neutral {
    background-color: #2c3e50;
}
.tip-widget {
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    font-size: 14px;
    color: #444;
}
.tip-widget strong {
    color: #2c3e50;
}
.tip-widget em {
    color: #555;
    font-style: italic;
}
.widget.bg-light {
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}
.list-group-item {
    font-size: 14px;
    padding: 10px 15px;
}
@media (max-width: 900px) {
    .atlas-dashboard-header-flex {
        flex-direction: column;
        align-items: flex-start;
    }
    .atlas-dashboard-greeting, .atlas-dashboard-clima {
        max-width: 100%;
        width: 100%;
        margin-left: 0;
        margin-top: 18px;
    }
}
@media (max-width: 768px) {
  .saludar, .clima {
    width: 100%;
    text-align: center;
  }
}
</style>