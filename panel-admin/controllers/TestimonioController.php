<?php
namespace app\controllers;

use app\models\Testimonio;
use app\models\TestimonioSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\helpers\LibreriaHelper;
use app\models\User;
use Yii;

class TestimonioController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'only'  => ['index','view','create','update','delete'],
                'rules' => [
                    [
                        'allow'         => true,
                        'roles'         => ['@'],
                        'matchCallback' => fn() => User::checkRoleByUserId(
                            Yii::$app->user->identity->usu_id,
                            [1,2,3]
                        ),
                    ],
                ],
            ],
            'verbs' => [
                'class'   => VerbFilter::class,
                'actions' => ['delete' => ['POST']],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel  = new TestimonioSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->setSort(false);
        $dataProvider->query->orderBy(['tes_orden' => SORT_ASC]);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($tes_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($tes_id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Testimonio();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->tes_slug = LibreriaHelper::generateSlug($model->tes_nombre);

            if ($model->save()) {
                $ruta = LibreriaHelper::subirFoto($model, 'tes_imagen', 'testimonios');
                if ($ruta !== null) {
                    $model->updateAttributes(['tes_imagen' => $ruta]);
                }
                return $this->redirect(['view', 'tes_id' => $model->tes_id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($tes_id)
    {
        $model     = $this->findModel($tes_id);
        $oldImagen = $model->tes_imagen;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->tes_slug = LibreriaHelper::generateSlug($model->tes_nombre);

            $file = UploadedFile::getInstance($model, 'tes_imagen');
            if (!$file) {
                $model->tes_imagen = $oldImagen;
            }

            if ($model->save()) {
                if ($file) {
                    $nuevaRuta = LibreriaHelper::subirFoto($model, 'tes_imagen', 'testimonios');
                    if ($nuevaRuta !== null) {
                        $model->updateAttributes(['tes_imagen' => $nuevaRuta]);
                    }
                }
                return $this->redirect(['view', 'tes_id' => $model->tes_id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($tes_id)
    {
        $model = $this->findModel($tes_id);

        if (!empty($model->tes_imagen) && file_exists(Yii::getAlias('@webroot') . "/recursos/uploads/{$model->tes_imagen}")) {
            unlink(Yii::getAlias('@webroot') . "/recursos/uploads/{$model->tes_imagen}");
        }

        $model->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($tes_id)
    {
        if (($model = Testimonio::findOne($tes_id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('El testimonio solicitado no existe.');
    }

    protected function subirFoto(Testimonio $model, $tes_id)
    {
        $file = UploadedFile::getInstance($model, 'tes_imagen');

        if ($model->validate() && $file) {
            $ext = $file->extension;
            $rutaTmp = Yii::getAlias('@webroot') . "/recursos/uploads/testimonios/{$tes_id}.{$ext}";
            if (!is_dir(dirname($rutaTmp))) {
                mkdir(dirname($rutaTmp), 0755, true);
            }
            if ($file->saveAs($rutaTmp)) {
                LibreriaHelper::resizeImage($rutaTmp, $rutaTmp, 600, 600);
                return "testimonios/{$tes_id}.{$ext}";
            }
        }

        return "";
    }

    public function actionUpdateOrder()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $orden = Yii::$app->request->post('orden', []);
        foreach ($orden as $item) {
            Testimonio::updateAll(['tes_orden' => $item['orden']], ['tes_id' => $item['id']]);
        }
        return ['success' => true, 'message' => 'Orden actualizado correctamente'];
    }
}