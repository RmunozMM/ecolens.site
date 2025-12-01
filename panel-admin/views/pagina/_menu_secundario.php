<?php
use yii\helpers\Html;
/** @var \app\models\Pagina[] $paginas */
?>
<table class="table table-sm table-bordered mb-0">
    <thead>
        <tr>
            <th>#</th>
            <th>Título</th>
            <th>Slug</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($paginas as $pagina): ?>
        <tr>
            <td><?= $pagina->pag_id ?></td>
            <td><?= Html::encode($pagina->pag_titulo) ?></td>
            <td><?= Html::encode($pagina->pag_slug) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>