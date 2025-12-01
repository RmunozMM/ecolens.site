<?php

namespace app\controllers;

use app\models\Correo;
use app\models\CorreoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use app\models\User;

/**
 * CorreoController implements the CRUD actions for Correo model.
 */
class CorreoController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'only' => ['index','view','create','update','delete'],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {

                            $usuario = Yii::$app->user->identity->usu_id;
                            return User::checkRoleByUserId($usuario,[1,2,3]);                                
                        },
                    ],
                    // everything else is denied

                ],
            ],
        ];
    }

    /**
     * Lists all Correo models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CorreoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Correo model.
     * @param int $cor_id ID del correo electrónico
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($cor_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($cor_id),
        ]);
    }

    /**
     * Creates a new Correo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Correo();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'cor_id' => $model->cor_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Correo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $cor_id ID del correo electrónico
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionUpdate($cor_id)
{
    $model = $this->findModel($cor_id);

    if ($this->request->isPost) {
        $model->load($this->request->post());
        $model->cor_estado = 'resuelto';
        $model->cor_fecha_respuesta = date('Y-m-d H:i:s');

        if ($model->save()) {
            $siteName   = Yii::$app->params['site_name'] ?? 'EcoLens';
            $siteDomain = Yii::$app->params['site_domain'] ?? 'ecolens.site';
            $recipient  = trim($model->cor_correo);
            $subject    = 'Respuesta a tu contacto en ' . $siteName;
            $sender     = 'contacto@' . $siteDomain;

            // HTML con diseño agradable para el cliente
            $html = <<<HTML
<!doctype html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>{$subject}</title>
</head>
<body style="margin:0;padding:0;background:#f6f8fa;font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Helvetica,Arial,sans-serif;">
  <table align="center" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;margin:auto;background:#ffffff;border-radius:8px;box-shadow:0 4px 16px rgba(0,0,0,.08);overflow:hidden;">
    <tr>
      <td style="background:#14532d;padding:24px;text-align:center;color:#fff;">
        <h1 style="margin:0;font-size:20px;">🌿 {$siteName}</h1>
        <p style="margin:4px 0 0;font-size:13px;opacity:.85;">Explorando la fauna chilena con inteligencia artificial</p>
      </td>
    </tr>
    <tr>
      <td style="padding:24px 32px;color:#334155;">
        <p style="font-size:15px;">Hola <strong>{$model->cor_nombre}</strong>,</p>
        <p style="font-size:15px;line-height:1.6;">
          Hemos revisado tu mensaje y te dejamos nuestra respuesta a continuación:
        </p>
        <div style="background:#f1f5f9;padding:16px 20px;border-left:4px solid #16a34a;margin:16px 0;border-radius:4px;font-size:15px;line-height:1.5;color:#1e293b;">
          {$model->cor_respuesta}
        </div>
        <p style="font-size:14px;color:#475569;">Si necesitas más ayuda, puedes responder directamente a este correo o visitar nuestro sitio web.</p>
        <p style="margin-top:24px;text-align:center;">
          <a href="https://{$siteDomain}" style="background:#16a34a;color:#fff;padding:10px 18px;text-decoration:none;border-radius:6px;font-weight:500;font-size:14px;">
            Ir a {$siteName}
          </a>
        </p>
      </td>
    </tr>
    <tr>
      <td style="background:#f8fafc;padding:12px;text-align:center;font-size:12px;color:#94a3b8;">
        Este mensaje fue generado automáticamente por <strong>{$siteName}</strong>.<br>
        © 2025 {$siteName}. Todos los derechos reservados.
      </td>
    </tr>
  </table>
</body>
</html>
HTML;

            // Envío usando PHPMailer vía LibreriaHelper
            $enviado = \app\helpers\LibreriaHelper::enviarCorreoHtml(
                $recipient,
                $subject,
                $html,
                $sender
            );

            if ($enviado) {
                Yii::$app->session->setFlash('success', 'Correo actualizado y enviado correctamente.');
            } else {
                Yii::$app->session->setFlash('error', 'El correo fue actualizado, pero no se pudo enviar la respuesta.');
            }

            return $this->redirect(['view', 'cor_id' => $model->cor_id]);
        } else {
            Yii::$app->session->setFlash('error', 'Ocurrió un error al actualizar el registro.');
        }
    }

    return $this->render('update', [
        'model' => $model,
    ]);
}
    

    /**
     * Deletes an existing Correo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $cor_id ID del correo electrónico
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($cor_id)
    {
        $this->findModel($cor_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Correo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $cor_id ID del correo electrónico
     * @return Correo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($cor_id)
    {
        if (($model = Correo::findOne(['cor_id' => $cor_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
