<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var array $menuItems */
/** @var int $totalMenus */

$this->title = 'Menús';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Html::a('Crear Menú', ['create'], ['class' => 'btn btn-success']) ?></p>

    <div class="summary">
        Mostrando <b>1-<?= $totalMenus ?></b> de <b><?= $totalMenus ?></b> elementos.
    </div>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th style="width:30px;">#</th>
            <th style="width:50px;">Ícono</th>
            <th>Nombre</th>
            <th>URL</th>
            <th>Etiqueta</th>
            <th style="width:75px;">Mostrar</th>
            <th>ID Padre</th>
            <th>Rol de seguridad</th>
            <th>Creado por</th>
            <th>Fecha creación</th>
            <th>Modificado por</th>
            <th>Fecha modificación</th>
            <th class="text-center">Orden</th>
            <th style="width:200px;">Acciones</th>
        </tr>
        </thead>

        <tbody id="sortable-menu">
        <?php foreach ($menuItems as $menuItem): ?>
            <?php
            $iconData = explode('|', $menuItem['nivel_1']->men_icono ?? '');
            $iconClass = $iconData[0] ?? 'bi-grid';
            $iconColor = $iconData[1] ?? '#223142';
            ?>
            <!-- NIVEL 1 -->
            <tr data-id="<?= $menuItem['nivel_1']->men_id ?>"
                data-level="1"
                data-parent="0"
                class="menu-item">
                <td><?= Html::encode($menuItem['nivel_1']->men_id) ?></td>
                <td style="text-align:center;">
                    <i class="bi <?= Html::encode($iconClass) ?>"
                       style="font-size:1.4em;color:<?= Html::encode($iconColor) ?>;"></i>
                </td>
                <td><strong><?= Html::encode($menuItem['nivel_1']->men_nombre) ?></strong></td>
                <td><?= Html::encode($menuItem['nivel_1']->men_url) ?></td>
                <td><?= Html::encode($menuItem['nivel_1']->men_etiqueta) ?></td>
                <td><?= Html::encode($menuItem['nivel_1']->men_mostrar) ?></td>
                <td><?= Html::encode($menuItem['nivel_1']->men_padre_id ?? '-') ?></td>
                <td><?= Html::encode($menuItem['nivel_1']->rol->rol_nombre ?? 'Sin Rol') ?></td>
                <td><?= Html::encode($menuItem['nivel_1']->createdByUser->usu_username ?? '—') ?></td>
                <td><?= Yii::$app->formatter->asDatetime($menuItem['nivel_1']->created_at) ?></td>
                <td><?= Html::encode($menuItem['nivel_1']->updatedByUser->usu_username ?? '—') ?></td>
                <td><?= Yii::$app->formatter->asDatetime($menuItem['nivel_1']->updated_at) ?></td>
                <td class="orden-cell text-center">
                    <i class="fa-solid fa-arrows-alt move-handle"></i>
                </td>
                <td>
                    <?= Html::a('<i class="fa-solid fa-eye"></i>',
                        ['menu/view', 'men_id' => $menuItem['nivel_1']->men_id],
                        ['class' => 'btn-action btn-view', 'title' => 'Ver Menú']) ?>

                    <?= Html::a('<i class="fa-solid fa-pen"></i>',
                        ['menu/update', 'men_id' => $menuItem['nivel_1']->men_id],
                        ['class' => 'btn-action btn-update', 'title' => 'Actualizar Menú']) ?>

                    <?= Html::a('<i class="fa-solid fa-trash"></i>',
                        ['menu/delete', 'men_id' => $menuItem['nivel_1']->men_id],
                        [
                            'class' => 'btn-action btn-delete',
                            'title' => 'Eliminar Menú',
                            'data-confirm' => '¿Eliminar este Menú?',
                            'data-method' => 'post',
                        ]) ?>

                    <?= Html::a(
                        ($menuItem['nivel_1']->men_mostrar === 'Si'
                            ? '<i class="fa-solid fa-eye-slash"></i>'
                            : '<i class="fa-solid fa-eye"></i>'
                        ),
                        ['menu/toggle-visibility', 'men_id' => $menuItem['nivel_1']->men_id],
                        [
                            'class' => 'btn-action btn-visibility ' .
                                ($menuItem['nivel_1']->men_mostrar === 'Si' ? 'visible' : 'oculto'),
                            'title' => ($menuItem['nivel_1']->men_mostrar === 'Si'
                                ? 'Ocultar Menú'
                                : 'Mostrar Menú'),
                            'data-method' => 'post',
                        ]
                    ) ?>
                </td>
            </tr>

            <!-- NIVEL 2 -->
            <?php foreach ($menuItem['nivel_2'] as $submenuItem): ?>
                <?php
                $iconData2 = explode('|', $submenuItem->men_icono ?? '');
                $iconClass2 = $iconData2[0] ?? 'bi-list';
                $iconColor2 = $iconData2[1] ?? '#223142';
                ?>
                <tr data-id="<?= Html::encode($submenuItem->men_id) ?>"
                    data-level="2"
                    data-parent="<?= Html::encode($submenuItem->men_padre_id) ?>"
                    class="submenu-item">
                    <td><?= Html::encode($submenuItem->men_id) ?></td>
                    <td style="text-align:center;">
                        <i class="bi <?= Html::encode($iconClass2) ?>"
                           style="font-size:1.2em;color:<?= Html::encode($iconColor2) ?>;"></i>
                    </td>
                    <td style="padding-left:20px;">➢ <?= Html::encode($submenuItem->men_nombre) ?></td>
                    <td><?= Html::encode($submenuItem->men_url) ?></td>
                    <td><?= Html::encode($submenuItem->men_etiqueta) ?></td>
                    <td><?= Html::encode($submenuItem->men_mostrar) ?></td>
                    <td><?= Html::encode($submenuItem->men_padre_id ?? '-') ?></td>
                    <td><?= Html::encode($submenuItem->rol->rol_nombre ?? 'Sin Rol') ?></td>
                    <td><?= Html::encode($submenuItem->createdByUser->usu_username ?? '—') ?></td>
                    <td><?= Yii::$app->formatter->asDatetime($submenuItem->created_at) ?></td>
                    <td><?= Html::encode($submenuItem->updatedByUser->usu_username ?? '—') ?></td>
                    <td><?= Yii::$app->formatter->asDatetime($submenuItem->updated_at) ?></td>
                    <td class="orden-cell text-center">
                        <i class="fa-solid fa-arrows-alt move-handle"></i>
                    </td>
                    <td>
                        <?= Html::a('<i class="fa-solid fa-eye"></i>',
                            ['menu/view', 'men_id' => $submenuItem->men_id],
                            ['class' => 'btn-action btn-view', 'title' => 'Ver Menú']) ?>

                        <?= Html::a('<i class="fa-solid fa-pen"></i>',
                            ['menu/update', 'men_id' => $submenuItem->men_id],
                            ['class' => 'btn-action btn-update', 'title' => 'Actualizar Menú']) ?>

                        <?= Html::a('<i class="fa-solid fa-trash"></i>',
                            ['menu/delete', 'men_id' => $submenuItem->men_id],
                            [
                                'class' => 'btn-action btn-delete',
                                'title' => 'Eliminar Menú',
                                'data-confirm' => '¿Eliminar este Menú?',
                                'data-method' => 'post',
                            ]) ?>

                        <?= Html::a(
                            ($submenuItem->men_mostrar === 'Si'
                                ? '<i class="fa-solid fa-eye-slash"></i>'
                                : '<i class="fa-solid fa-eye"></i>'
                            ),
                            ['menu/toggle-visibility', 'men_id' => $submenuItem->men_id],
                            [
                                'class' => 'btn-action btn-visibility ' .
                                    ($submenuItem->men_mostrar === 'Si' ? 'visible' : 'oculto'),
                                'title' => ($submenuItem->men_mostrar === 'Si'
                                    ? 'Ocultar Menú'
                                    : 'Mostrar Menú'),
                                'data-method' => 'post',
                            ]
                        ) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
// Tus includes originales
$this->registerJsFile('https://code.jquery.com/jquery-3.6.0.min.js', [
    'position' => \yii\web\View::POS_HEAD,
]);
$this->registerJsFile('https://code.jquery.com/ui/1.12.1/jquery-ui.min.js', [
    'depends' => [\yii\web\JqueryAsset::class],
]);
$this->registerCssFile('https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css');

// Script de sortable: SOLO vista, no toca el controlador
$updateOrderUrl = Url::to(['menu/update-order']);
$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->csrfToken;

$js = <<<JS
(function($){
  $(function() {
    var \$tbody = $("#sortable-menu");

    if (!\$tbody.length || !$.fn.sortable) {
      console.warn("[Menu] No se encontró #sortable-menu o no está jQuery UI sortable disponible.");
      return;
    }

    \$tbody.sortable({
      handle: ".move-handle",
      helper: function(e, tr) {
        var \$originals = tr.children();
        var \$helper = tr.clone();
        \$helper.children().each(function(index) {
          $(this).width(\$originals.eq(index).width());
        });
        return \$helper;
      },
      update: function(event, ui) {
        var orden = [];
        \$tbody.children("tr").each(function(index) {
          var id = $(this).data("id");
          if (!id) return;
          orden.push({
            id: id,
            orden: index + 1
          });
        });

        if (!orden.length) {
          console.warn("[Menu] No hay filas para enviar en orden.");
          return;
        }

        $.ajax({
          url: "$updateOrderUrl",
          type: "POST",
          dataType: "json",
          data: {
            orden: orden,
            "$csrfParam": "$csrfToken"
          },
          success: function(resp) {
            if (!resp || resp.status !== "success") {
              console.error("[Menu] Error al guardar el orden:", resp);
            } else {
              console.log("[Menu] Orden actualizado correctamente.");
            }
          },
          error: function(xhr) {
            console.error("[Menu] Error HTTP en update-order:", xhr.status, xhr.responseText);
          }
        });
      }
    }).disableSelection();
  });
})(jQuery);
JS;

$this->registerJs($js);
?>

<style>
.menu-index table { table-layout: fixed; }
.orden-cell { text-align: center; width: 50px; }
.move-handle { cursor: grab; }
.move-handle:active { cursor: grabbing; }

.btn-action {
    margin-right:5px;
    padding:6px;
    border-radius:4px;
    color:#fff;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    transition:transform .2s ease, background-color .2s ease;
}
.btn-action:hover {
    transform:scale(1.1);
    background-color:rgba(0,0,0,0.1);
}
.btn-view { background-color:#3498db; }
.btn-update { background-color:#27ae60; }
.btn-delete { background-color:#e74c3c; }

/* VISIBILIDAD INVERTIDA (gris cuando visible, verde cuando oculto) */
.menu-index .btn-visibility.visible {
    background-color:#7f8c8d !important;   /* visible → gris */
    color:#fff !important;
}
.menu-index .btn-visibility.visible:hover {
    background-color:#6b7578 !important;
}
.menu-index .btn-visibility.oculto {
    background-color:#27ae60 !important;   /* oculto → verde */
    color:#fff !important;
}
.menu-index .btn-visibility.oculto:hover {
    background-color:#219150 !important;
}

.sr-only {
    position:absolute;
    width:1px;
    height:1px;
    padding:0;
    margin:-1px;
    overflow:hidden;
    clip:rect(0,0,0,0);
    border:0;
}
</style>
