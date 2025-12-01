<?php

namespace app\controllers;

use app\models\Users;
use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use app\models\FormRegister;
use yii\widgets\ActiveForm;
use app\controllers\Html;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\data\Pagination;
use app\helpers\LibreriaHelper;
use yii\helpers\Json;

/**
 * UserController implements the CRUD actions for Users model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'only' => ['index','view','create','update','delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            $usuario = Yii::$app->user->identity->usu_id;
                            return User::checkRoleByUserId($usuario,[1,2,3]);                                
                        },
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $msg ="";

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'msg' => $msg,
        ]);
    }

    public function actionView($usu_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($usu_id),
        ]);
    }

    /**
     * Crea un nuevo usuario y envía correo de verificación con LibreriaHelper (Brevo)
     */
    public function actionCreate()
    {
        $model = new FormRegister;
        $msg = "";

        // Validación AJAX
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $table = new Users;
                $table->usu_username = $model->usu_username;
                $table->usu_email = $model->usu_email;
                $model->usu_password = date("Y") . "_" . $model->usu_username;
                $table->usu_password = crypt($model->usu_password, Yii::$app->params["salt"]);
                $table->usu_authKey = $this->randkey("abcdef0123456789", 200);
                $table->usu_accessToken = $this->randkey("abcdef0123456789", 200);
                $table->usu_imagen = "";
                $table->usu_letra = 10;
                $table->usu_rol_id = $model->usu_rol_id ?: 3;
                $table->usu_email_verificado = "NO";

                if ($table->insert()) {
                    $user = $table->find()->where(["usu_email" => $model->usu_email])->one();
                    $token = md5($user->usu_email . '.' . $user->usu_username);
                    $base_url = Yii::$app->request->hostInfo . Yii::$app->getUrlManager()->getBaseUrl();

                    // Construcción del correo HTML de verificación
                    $subject = "Bienvenido a " . Yii::$app->params["site_name"];
                    $body = "
                        <h1>Bienvenido a " . Yii::$app->params["site_name"] . "</h1>
                        <p>Gracias por registrarte. Para verificar tu correo electrónico, haz clic en el siguiente enlace:</p>
                        <p><a href='" . $base_url . "/user/verificarmail?token=" . $token . "' style='background:#4CAF50;color:#fff;padding:10px 15px;text-decoration:none;border-radius:4px;'>Verificar correo electrónico</a></p>
                        <hr>
                        <p><strong>Usuario:</strong> {$model->usu_username}</p>
                        <p><strong>Contraseña:</strong> {$model->usu_password}</p>
                        <p style='margin-top:20px;font-size:12px;color:#888;'>Mensaje generado automáticamente el " . date('d/m/Y H:i') . ".</p>
                    ";

                    // Envío con Brevo usando LibreriaHelper
                    $ok = LibreriaHelper::enviarCorreoHtml(
                        $user->usu_email,
                        $subject,
                        $body,
                        'admin@ecolens.site'
                    );

                    if ($ok) {
                        $model->usu_username = null;
                        $model->usu_email = null;
                        $model->usu_password = null;
                        $msg = 'Usuario agregado. Se ha enviado un correo para verificar tu cuenta.';
                    } else {
                        $msg = 'Usuario creado, pero falló el envío del correo de verificación.';
                    }
                } else {
                    $msg = "Ha ocurrido un error al realizar el registro.";
                }
            } else {
                $model->getErrors();
            }
        }

        if ($msg == null) $msg = "";

        return $this->render("create", ["model" => $model, "msg" => $msg]);
    }

    /**
     * Confirma activación del usuario mediante enlace de correo.
     */
    public function actionConfirmar()
    {
        $table = new Users;
        if (Yii::$app->request->get()) {
            $usu_id = $_GET["usu_id"];
            $usu_authkey = $_GET["usu_authkey"];

            if ((int) $usu_id) {
                $model = $table
                    ->find()
                    ->where("usu_id=:usu_id", [":usu_id" => $usu_id])
                    ->andWhere("usu_authkey=:usu_authkey", [":usu_authkey" => $usu_authkey]);

                if ($model->count()) {
                    $activar = Users::findOne($usu_id);
                    $activar->usu_activate = "SI";
                    $activar = $activar->update();
                    if ($activar) {
                        echo "Felicitaciones, tu usuario ha sido activado. Te redireccionaremos al sitio en unos instantes";
                        echo "<meta http-equiv='refresh' content='8; " . Url::toRoute("site/login") . "'>";
                    } else {
                        echo "Ha ocurrido un error al realizar el registro. Te redireccionaremos al sitio en unos instantes";
                        echo "<meta http-equiv='refresh' content='8; " . Url::toRoute("site/login") . "'>";
                    }
                } else {
                    return $this->redirect(["user/confirmar"]);
                }
            } else {
                return $this->redirect(["user/confirmar"]);
            }
        } else {
            echo "Por favor contáctate con el administrador.";
        }
    }

    public function actionUpdate($usu_id)
    {
        $currentUserId = Yii::$app->user->identity->usu_id;

        // Si NO es admin y está intentando editar a otro usuario, denegamos
        if ($currentUserId != 1 && $currentUserId != $usu_id) {
            Yii::$app->session->setFlash('error', 'No tienes permisos para modificar este registro.');
            return $this->redirect(['index']);
        }

        $model = $this->findModel($usu_id);
        $msg = "";

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            $msg = "Registro actualizado.";
        }

        return $this->render('update', [
            'model' => $model,
            'msg' => $msg ?? "",
        ]);
    }

    public function actionDelete($usu_id)
    {
        if ($usu_id == 1) {
            Yii::$app->session->setFlash('error', 'No puedes eliminar este usuario protegido.');
            return $this->redirect(['index']);
        }

        $this->findModel($usu_id)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($usu_id)
    {
        if (($model = Users::findOne(['usu_id' => $usu_id])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    private function randkey($str = '', $long = 0)
    {
        $key = null;
        $str = str_split($str);
        $start = 0;
        $limit = count($str) - 1;
        for ($x = 0; $x < $long; $x++) {
            $key .= $str[rand($start, $limit)];
        }
        return $key;
    }

    public function actionActivate($usu_id)
    {
        $model = $this->findModel($usu_id);
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        if ($model->usu_activate == "SI") {
            $model->usu_activate = "NO";
            $msg = "Usuario desactivado.";
        } else {
            $model->usu_activate = "SI";
            $msg = "Usuario activado.";
        }

        $model->save();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
            'msg' => $msg,
        ]);
    }

    /**
     * Genera y envía una nueva contraseña al correo del usuario (usando Brevo).
     */
    public function actionSetpassword($usu_id)
    {
        $model = $this->findModel($usu_id);
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $usu_password = date("Y") . "_" . $model->usu_username;
        $model->usu_password = crypt($usu_password, Yii::$app->params["salt"]);
        $model->save();

        $subject = "Password actualizada en " . Yii::$app->params["site_name"];
        $body = "
            <h1>Se ha actualizado tu contraseña</h1>
            <p><strong>Usuario:</strong> {$model->usu_username}</p>
            <p><strong>Nueva contraseña:</strong> {$usu_password}</p>
            <p>Recuerda actualizar tu contraseña en tu perfil.</p>
            <p style='margin-top:20px;font-size:12px;color:#888;'>Mensaje generado automáticamente el " . date('d/m/Y H:i') . ".</p>
        ";

        // Envío con Brevo
        LibreriaHelper::enviarCorreoHtml(
            $model->usu_email,
            $subject,
            $body,
            'admin@ecolens.site'
        );

        $msg = "Contraseña enviada al correo del usuario.";
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
            'msg' => $msg,
        ]);
    }
public function actionMyprofile($usu_id = null)
{
    $currentUserId = Yii::$app->user->identity->usu_id;

    // Si no es admin (ID 1), solo puede editar su propio perfil
    if ($currentUserId != 1) {
        $usu_id = $currentUserId;
    } else {
        $usu_id = $usu_id ?? 1;
    }

    $model = $this->findModel($usu_id);
    $imagenAntigua = $model->usu_imagen;
    $msg = "";

    if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {

        // Guardamos imagen con LibreriaHelper como en Taxonomia
        $rutaImagen = \app\helpers\LibreriaHelper::subirFoto($model, 'usu_imagen', 'users');

        if ($rutaImagen) {
            // Eliminamos la antigua si existía
            if (!empty($imagenAntigua)) {
                $rutaFisica = Yii::getAlias("@app/../recursos/uploads/{$imagenAntigua}");
                if (file_exists($rutaFisica)) {
                    @unlink($rutaFisica);
                }
            }
            $model->usu_imagen = $rutaImagen;
        } else {
            // Si no se subió nada, mantener la anterior
            $model->usu_imagen = $imagenAntigua;
        }

        $model->updated_at = date('Y-m-d H:i:s');
        $model->updated_by = $currentUserId;

        if ($model->save(false)) {
            $msg = "Perfil actualizado correctamente.";
        } else {
            $msg = "Error al guardar el perfil.";
        }
    }

    return $this->render('myprofile', [
        'model' => $model,
        'msg'   => $msg,
    ]);
}


    public function actionMypassword($usu_id)
    {
        $model = $this->findModel($usu_id);
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $msg = "";

        if (Yii::$app->request->post()) {
            $pass1 = $_POST["usu_password1"];
            $pass2 = $_POST["usu_password2"];

            if ($pass1 == $pass2) {
                $model->usu_password = crypt($pass1, Yii::$app->params["salt"]);
                $model->save();
                $msg = "Contraseña actualizada.";
            } else {
                $msg = "Las contraseñas no coinciden.";
            }
        }

        return $this->render('mypassword', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
            'msg' => $msg,
        ]);
    }


    protected function subirFoto(Users $model, $usu_id)
    {
        // Obtenemos el archivo subido desde el formulario
        $uploadedFile = UploadedFile::getInstance($model, 'usu_imagen');

        if ($uploadedFile !== null) {
            // Directorio base (fuera de panel-admin/web)
            $uploadDir = Yii::getAlias('@app') . '/../../recursos/uploads/users/';

            // Aseguramos que la carpeta exista
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0775, true);
            }

            // Nombre final del archivo: ID + extensión
            $fileName = $usu_id . '.' . strtolower($uploadedFile->extension);
            $rutaArchivo = $uploadDir . $fileName;

            // Guardamos físicamente
            if ($uploadedFile->saveAs($rutaArchivo)) {
                // Redimensionamos (opcional)
                \app\helpers\LibreriaHelper::resizeImage($rutaArchivo, $rutaArchivo, 600, 600);

                // Guardamos ruta relativa en BD
                // (para usarla como 'users/5.jpg' desde FrontWidget)
                return 'users/' . $fileName;
            }
        }

        // Si no hay archivo o falla el guardado
        return "";
    }

    public function actionLetra()
    {
        $usuario = Yii::$app->user->identity->usu_id;
        $model = $this->findModel($usuario);

        if ($model) {
            $cantidad = Yii::$app->request->post('cantidad');
            $model->usu_letra += ($cantidad == 1) ? 1 : -1;

            if ($model->save()) {
                return Json::encode(['nuevoTamanio' => $model->usu_letra]);
            } else {
                Yii::$app->session->setFlash('error', 'No se pudo guardar el cambio de tamaño de letra.');
            }
        }
        return Json::encode(['error' => 'Usuario no autenticado.']);
    }

    public function actionMymail($usu_id)
    {
        $currentUser = Yii::$app->user->identity;
        if ($currentUser->usu_id != $usu_id && $currentUser->usu_rol_id != 1) {
            throw new \yii\web\ForbiddenHttpException("No tiene permisos para actualizar este correo electrónico.");
        }

        $user = $this->findModel($usu_id);

        $formModel = new \yii\base\DynamicModel(['new_email', 'confirm_email', 'current_password']);
        $formModel->addRule(['new_email', 'confirm_email', 'current_password'], 'required')
            ->addRule(['new_email', 'confirm_email'], 'email')
            ->addRule(['confirm_email'], 'compare', [
                'compareAttribute' => 'new_email',
                'message' => 'Los correos deben coincidir.'
            ])
            ->addRule(['current_password'], 'string', ['min' => 6]);

        if ($formModel->load(Yii::$app->request->post()) && $formModel->validate()) {
            $providedPassword = $formModel->current_password;
            if (crypt($providedPassword, $user->usu_password) !== $user->usu_password) {
                $formModel->addError('current_password', 'La contraseña actual es incorrecta.');
            } else {
                $existing = Users::find()
                    ->where(['usu_email' => $formModel->new_email])
                    ->andWhere(['<>', 'usu_id', $user->usu_id])
                    ->one();
                if ($existing) {
                    $formModel->addError('new_email', 'El correo electrónico ya está en uso.');
                } else {
                    $user->usu_email = $formModel->new_email;
                    $user->usu_email_verificado = "NO";
                    if ($user->save(false)) {
                        Yii::$app->session->setFlash('success', 'Correo electrónico actualizado correctamente.');
                        return $this->redirect(['view', 'usu_id' => $user->usu_id]);
                    } else {
                        Yii::$app->session->setFlash('error', 'No se pudo actualizar el correo electrónico.');
                    }
                }
            }
        }

        return $this->render('mymail', [
            'user' => $user,
            'formModel' => $formModel,
        ]);
    }

    /**
     * Verifica el correo del usuario y activa su cuenta.
     * Cambia ambos campos: usu_email_verificado = "SI" y usu_activate = "SI"
     */
    public function actionVerificarmail($token)
    {
        // Buscamos al usuario según el token MD5
        $user = Users::find()
            ->where(new \yii\db\Expression("MD5(CONCAT(usu_email, '.', usu_username)) = :token"), [':token' => $token])
            ->one();

        if ($user !== null) {
            // Si ya estaba verificado y activo, informamos
            if ($user->usu_email_verificado === "SI" && $user->usu_activate === "SI") {
                Yii::$app->session->setFlash('info', 'Tu cuenta ya se encuentra verificada y activa.');
            } else {
                // Marcamos ambos campos
                $user->usu_email_verificado = "SI";
                $user->usu_activate = "SI";

                if ($user->save(false)) {
                    Yii::$app->session->setFlash('success', '¡Tu cuenta ha sido verificada y activada correctamente!');
                } else {
                    Yii::$app->session->setFlash('error', 'Ocurrió un problema al activar tu cuenta. Inténtalo más tarde.');
                }
            }
        } else {
            Yii::$app->session->setFlash('error', 'El enlace de verificación no es válido o ha expirado.');
        }

        return $this->redirect(['site/login']);
    }
}