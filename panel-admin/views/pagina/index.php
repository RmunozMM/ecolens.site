<?php

use app\models\Pagina;
use app\models\Users;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\StringHelper;
use yii\grid\GridView;
use app\widgets\MassUploadWidget;
use app\widgets\ExportRecordsWidget;
use app\helpers\AuditoriaGridColumns;
use app\widgets\CrudActionButtons;

$this->title = 'Páginas';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="pagina-index">
    <h2><?= Html::encode($this->title) ?></h2>

    <div class="d-flex justify-content-between">
        <div class="btn-group" role="group">
            <?= Html::a('<i class="fa fa-plus"></i> Crear Página', ['create'], ['class' => 'btn btn-success']) ?>

            <button type="button" class="btn btn-primary" id="verMenuPrincipal">
                <i class="fa fa-list"></i> Ver menú Principal
            </button>
            <button type="button" class="btn btn-secondary" id="verMenuSecundario">
                <i class="fa fa-list-alt"></i> Ver menú Secundario
            </button>
        </div>
        <div>
            <?= ExportRecordsWidget::widget([
                'modelClass' => 'app\models\Pagina',
                'exportUrl'  => ['export/index'],
            ]) ?>
            <?= MassUploadWidget::widget([
                'modelClass' => 'app\models\Pagina',
                'modelLabel' => 'Páginas',
                'fieldsMap'  => [
                    'Título'   => 'pag_titulo',
                    'Estado'   => 'pag_estado',
                    'Posición' => 'pag_posicion',
                    'Slug'     => 'pag_slug',
                    'Creación' => 'pag_creacion',
                ],
                'uploadUrl' => ['import/index'],
            ]) ?>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'options'      => ['id' => 'sortable-pages'],
        'rowOptions'   => fn($model) => [
            'data-id' => $model->pag_id,
            'class'   => strtolower($model->pag_estado) === 'publicado' ? '' : 'grisado',
        ],
        'columns'      => [
            ['attribute' => 'pag_id', 'label' => 'ID', 'headerOptions' => ['style' => 'width:60px']],
            ['attribute' => 'pag_titulo', 'label' => 'Título'],
            'pag_acceso',
            ['attribute' => 'pag_slug', 'label' => 'Slug'],
            [
                'attribute' => 'pag_modo_contenido',
                'label'     => 'Administración',
                'filter'    => [
                    'autoadministrable' => 'Autoadministrable',
                    'administrado_programador' => 'Administrado por Programador',
                ],
                'value' => fn($m) => $m->getModoContenidoLabel(),
            ],
            [
                'attribute' => 'pag_fuente_contenido',
                'label'     => 'Fuente de Contenido',
                'filter'    => [
                    'usar_plantilla' => 'Usar plantilla',
                    'editar_directo' => 'Editar directamente',
                ],
                'value' => fn($m) => $m->pag_fuente_contenido === 'usar_plantilla'
                    ? 'Usar plantilla'
                    : 'Editar directamente',
            ],
            [
                'attribute' => 'pag_estado',
                'label'     => '¿Publicado?',
                'filter'    => ['borrador' => 'Borrador', 'publicado' => 'Publicado'],
            ],
            [
                'attribute' => 'pag_mostrar_menu',
                'label'     => 'Mostrar en Menú Principal',
                'filter'    => ['SI' => 'SI', 'NO' => 'NO'],
            ],
            [
                'attribute' => 'pag_mostrar_menu_secundario',
                'label'     => 'Mostrar en Menú Secundario',
                'filter'    => ['SI' => 'SI', 'NO' => 'NO'],
            ],
            ['attribute' => 'pag_label', 'label' => 'Etiqueta'],
            [
                'attribute' => 'pag_css_programador',
                'label'     => 'CSS Programador',
                'format'    => 'ntext',
                'value'     => fn($m) => StringHelper::truncate($m->pag_css_programador, 50, '...'),
            ],
            AuditoriaGridColumns::createdBy(),
            AuditoriaGridColumns::createdAt(),
            AuditoriaGridColumns::updatedBy(),
            AuditoriaGridColumns::updatedAt(),
            [
                'attribute' => 'pag_posicion',
                'label'     => 'Orden',
                'format'    => 'raw',
                'value'     => fn($m) => '<i class="fa-solid fa-arrows-alt move-handle" style="cursor:grab;"></i>',
                'headerOptions'  => ['class' => 'text-right', 'style' => 'width:80px'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            CrudActionButtons::column([
                'actions'        => ['view', 'publish', 'update', 'delete', 'manageGaleria', 'duplicate'],
                'idAttribute'    => 'pag_id',
                'nombreRegistro' => 'Página',
            ]),
        ],
    ]); ?>
</div>

<!-- ✅ LIGHTBOX PROPIO -->
<div id="ecOverlay" class="ec-overlay" aria-hidden="true">
  <div class="ec-modal" role="dialog" aria-modal="true" aria-labelledby="ecModalTitle">
    <div class="ec-modal-header">
      <h5 id="ecModalTitle" class="ec-modal-title">Ver Menú</h5>
      <button type="button" class="ec-close" aria-label="Cerrar">&times;</button>
    </div>
    <div id="ecModalBody" class="ec-modal-body"></div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  // ---------- LIGHTBOX ----------
  const overlay = document.getElementById('ecOverlay');
  const modal   = overlay.querySelector('.ec-modal');
  const closeBtn= overlay.querySelector('.ec-close');
  const titleEl = document.getElementById('ecModalTitle');
  const bodyEl  = document.getElementById('ecModalBody');

  async function openLightbox(url, titulo){
    titleEl.textContent = titulo || 'Ver Menú';
    bodyEl.innerHTML = `
      <div style="text-align:center;padding:24px;">
        <div style="width:42px;height:42px;border:4px solid #ddd;border-top-color:#198754;border-radius:50%;margin:8px auto;animation:spin .8s linear infinite;"></div>
        <p style="color:#666;margin:0;">Cargando…</p>
      </div>`;
    overlay.classList.add('is-open');
    document.body.classList.add('ec-scroll-lock');
    try {
      const res  = await fetch(url, { credentials: 'same-origin' });
      const html = await res.text();
      bodyEl.innerHTML = html;
    } catch (e) {
      console.error(e);
      bodyEl.innerHTML = `<div style="color:#b91c1c;">Error al cargar contenido.</div>`;
    }
  }

  function closeLightbox(){
    overlay.classList.remove('is-open');
    document.body.classList.remove('ec-scroll-lock');
    bodyEl.innerHTML = '';
  }

  closeBtn.addEventListener('click', closeLightbox);
  overlay.addEventListener('click', e => { if (!modal.contains(e.target)) closeLightbox(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && overlay.classList.contains('is-open')) closeLightbox(); });

  document.getElementById('verMenuPrincipal').addEventListener('click', () => {
    openLightbox('<?= Url::to(['pagina/menu-principal']) ?>', 'Menú Principal');
  });
  document.getElementById('verMenuSecundario').addEventListener('click', () => {
    openLightbox('<?= Url::to(['pagina/menu-principal-secundario']) ?>', 'Menú Secundario');
  });

  // ---------- DRAG & DROP REORDENAMIENTO ----------
  const updateOrderUrl = '<?= Url::to(['pagina/update-order']) ?>';
  $("#sortable-pages table tbody").sortable({
      handle: ".move-handle",
      update: function() {
          const order = [];
          $("#sortable-pages table tbody tr").each(function(index){
              order.push({ id: $(this).data("id"), position: index + 1 });
          });
          $.ajax({
              url: updateOrderUrl,
              type: "POST",
              data: { order },
              success: res => console.log("Orden guardado:", res),
              error: () => alert("Error al guardar el orden.")
          });
      }
  }).disableSelection();
});
</script>

<style>
@keyframes spin {to{transform:rotate(360deg)}}
.ec-overlay{
  position:fixed; inset:0; background:rgba(0,0,0,.75);
  display:none; align-items:center; justify-content:center;
  z-index:1055;
}
.ec-overlay.is-open{display:flex;}
.ec-modal{
  width:min(900px,92vw); max-height:86vh; overflow:auto;
  background:#fff; border-radius:12px; box-shadow:0 12px 40px rgba(0,0,0,.45);
  transform:scale(.97); opacity:0; transition:.18s ease-in-out;
}
.ec-overlay.is-open .ec-modal{transform:scale(1); opacity:1;}
.ec-modal-header{
  display:flex; align-items:center; justify-content:space-between;
  padding:12px 16px; border-bottom:1px solid #e9e9e9;
}
.ec-modal-title{margin:0;font-weight:700;}
.ec-close{
  font-size:26px; line-height:1; border:0; background:transparent; cursor:pointer;
}
.ec-modal-body{padding:16px;}
.ec-scroll-lock{overflow:hidden;}
.grisado {
  background-color:#f5f5f5!important;
  color:#b0b0b0!important;
  opacity:0.65;
}
.grisado td,.grisado a,.grisado i {
  color:#b0b0b0!important;
  pointer-events:none;
  text-decoration:none;
  filter:grayscale(.6);
}
.grisado .btn-action {
  pointer-events:none;
  filter:grayscale(.7);
  background-color:#ccc!important;
  color:#b0b0b0!important;
  border:none;
}
.grisado .btn-publish {
  pointer-events:auto!important;
  filter:none!important;
  background-color:#27ae60!important;
  color:#fff!important;
  border:none;
}
#verMenuPrincipal, #verMenuSecundario { margin-left:12px; }
</style>

<?php
// Cargar dependencias jQuery UI necesarias para sortable
$this->registerJsFile(
    'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);
$this->registerCssFile('https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css');
?>