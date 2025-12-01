<?php
/** @var string $q */
/** @var array $results */

use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\widgets\ListView;

$this->title = "Resultados de búsqueda: " . Html::encode($q);
?>

<h2 class="mb-4">Resultados para “<?= Html::encode($q) ?>”</h2>

<?php if (empty($results)): ?>
    <p>No se encontraron resultados.</p>
<?php else: ?>
    <div class="accordion" id="searchResultsAccordion">
        <?php foreach ($results as $index => $block): 
            $modelClass = $block['dataProvider']->query->modelClass;
            $tableName = $modelClass::tableName();

            if ($tableName === 'roles') {
                $displayName = 'Rol';
            } else {
                $displayName = Inflector::camel2words(str_replace('_', ' ', $tableName));
            }

            $count = $block['dataProvider']->getTotalCount();
            $collapseId = "collapse{$index}";
            $headingId = "heading{$index}";
        ?>
            <div class="accordion-item mb-3">
                <h2 class="accordion-header" id="<?= $headingId ?>">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>" aria-expanded="false" aria-controls="<?= $collapseId ?>">
                        <?= Html::encode($displayName) ?> (<?= $count ?> registro<?= $count !== 1 ? 's' : '' ?>)
                    </button>
                </h2>
                <div id="<?= $collapseId ?>" class="accordion-collapse collapse" aria-labelledby="<?= $headingId ?>" data-bs-parent="#searchResultsAccordion">
                    <div class="accordion-body">
                        <?= ListView::widget([
                            'dataProvider' => $block['dataProvider'],
                            'layout' => '
                                <div class="row g-3">
                                    {items}
                                </div>
                                <div class="text-center mt-3">{pager}</div>
                            ',
                            'itemOptions' => ['tag' => false],
                            'itemView' => function ($model, $key, $index, $widget) {
                                $modelClass = get_class($model);
                                $tableName = call_user_func([$modelClass, 'tableName']);
                                $controller = strtolower(Inflector::singularize($tableName));
                                $prefix = substr($controller, 0, 3);
                                $pkAttr = $prefix . '_id';
                                $titleAttr = $prefix . '_titulo';

                                if (!property_exists($model, $titleAttr) || empty($model->$titleAttr)) {
                                    $safeAttrs = $model->safeAttributes();
                                    $titleAttr = reset($safeAttrs);
                                }

                                $title = $model->$titleAttr ?? 'Sin título';
                                $pkValue = $model->$pkAttr ?? null;
                                $url = [$controller . '/view', $pkAttr => $pkValue];

                                return Html::tag('div', '
                                    <div class="card search-card h-100">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="fa fa-file-alt me-2 text-primary"></i>' . Html::encode($title) . '
                                            </h6>
                                            <p class="card-text small mb-0">Origen: <strong>' . ucfirst($controller) . '</strong></p>
                                        </div>
                                        <div class="card-footer bg-transparent border-0 text-end">
                                            ' . Html::a('<i class="fa fa-arrow-right"></i> Ver detalle', $url, ['class' => 'btn btn-sm btn-outline-primary']) . '
                                        </div>
                                    </div>
                                ', ['class' => 'col-md-4']);
                            },
                            'summary' => false,
                        ]); ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<style>
.search-card {
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    border-radius: 8px;
    border: 1px solid #eee;
    transition: all 0.2s ease;
}
.search-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transform: translateY(-2px);
}
.search-card .card-title {
    font-weight: 600;
    font-size: 15px;
}
.search-card .card-footer {
    text-align: right;
}
.accordion-button {
    font-weight: 600;
    font-size: 15px;
}
.accordion-body {
    background-color: #fcfcfc;
}
</style>