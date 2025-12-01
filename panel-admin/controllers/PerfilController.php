<?php

namespace app\controllers;

use app\models\Perfil;
use app\models\PerfilSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
use yii\web\UploadedFile;
use app\helpers\LibreriaHelper;

/**
 * PerfilController implements the CRUD actions for Perfil model.
 */
class PerfilController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Perfil models.
     *
     * @return string
     */
    /**
     * Finds the Perfil model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $per_id Per ID
     * @return Perfil the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($per_id)
    {
        if (($model = Perfil::findOne(['per_id' => $per_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionProfile()
{
    $model = $this->findModel(1);
    
    $imagenAntigua = $model->per_imagen;

    if ($this->request->isPost && $model->load($this->request->post())) {

        // Subir la nueva imagen si se ha cambiado
        $rutaImagen =LibreriaHelper::subirFoto($model, 'per_imagen', 'perfil');

        if ($rutaImagen) {
            if (!empty($imagenAntigua) && file_exists("../../recursos/uploads/perfil/" . $imagenAntigua)) {
                unlink("../../recursos/uploads/perfil/" . $imagenAntigua);
            }
            $model->per_imagen = $rutaImagen;
        } else {
            $model->per_imagen = $imagenAntigua;
        }

        // 👉 AQUÍ FALTABA: guardar los cambios en la BD
        if ($model->save()) {
            \Yii::$app->session->setFlash('success', 'Perfil actualizado exitosamente.');
            return $this->refresh();
        } else {
            \Yii::$app->session->setFlash('error', 'Error al guardar el perfil.');
        }
    }

    return $this->render('profile', ['model' => $model]);
}

}
