<?php
namespace app\modules\api\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\web\BadRequestHttpException;
use app\models\Observador;
use app\helpers\LibreriaHelper;

class ObservadorController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * CORS + formato de respuesta:
     * - JSON por defecto
     * - HTML solo en actionActivar
     */
    public function beforeAction($action)
    {
        if ($action->id !== 'activar') {
            Yii::$app->response->format = Response::FORMAT_JSON;
        }

        $req     = Yii::$app->request;
        $headers = Yii::$app->response->headers;

        // Tomamos el Origin real si viene, si no usamos hostInfo (https://dominio)
        $origin = $req->headers->get('Origin');
        if (!$origin) {
            $origin = rtrim($req->hostInfo ?? '*', '/');
        }

        // Con credenciales, no se puede usar "*"
        $headers->set('Access-Control-Allow-Origin', $origin);
        $headers->set('Vary', 'Origin');
        $headers->set('Access-Control-Allow-Credentials', 'true');
        $headers->set('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, Authorization');
        $headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');

        if ($req->isOptions) {
            // Preflight no debe seguir al controlador
            Yii::$app->response->statusCode = 204;
            Yii::$app->end();
        }

        return parent::beforeAction($action);
    }

    /* ===========================
       Helpers
       =========================== */

    /** Lee params desde JSON o POST */
    private function getBodyParam(string $key, $default = null)
    {
        $req = Yii::$app->request;
        $val = $req->post($key, null);

        if ($val === null && stripos((string)$req->contentType, 'application/json') !== false) {
            $json = json_decode($req->getRawBody(), true) ?: [];
            if (array_key_exists($key, $json)) {
                $val = $json[$key];
            }
        }
        return ($val === null || $val === '') ? $default : $val;
    }

    /** Normaliza email a minúsculas. */
    private function normalizeEmail(?string $email): ?string {
        $email = trim((string)$email);
        return $email !== '' ? mb_strtolower($email) : null;
    }

    /** Normaliza username (minúsculas, solo [a-z0-9._-]). */
    private function normalizeUsername(?string $u): ?string {
        $u = trim((string)$u);
        if ($u === '') return null;
        $u = mb_strtolower($u);
        $u = preg_replace('~[^a-z0-9._-]+~', '', $u);
        return $u ?: null;
    }

    /** Carga de entorno robusta para construir URLs */
    private function loadEnv(): array {
        $cands = [
            Yii::getAlias('@app') . '/config/ecolens_env.php',
            dirname(Yii::getAlias('@app')) . '/panel-admin/config/ecolens_env.php',
        ];
        foreach ($cands as $p) {
            if (is_file($p)) {
                $env = require $p;
                if (is_array($env)) return $env;
            }
        }
        $isLocal = isset($_SERVER['HTTP_HOST']) && preg_match('/^(localhost|127\.0\.0\.1)(:\d+)?$/i', $_SERVER['HTTP_HOST']);
        $prefix  = $isLocal ? '/ecolens.site' : '';
        return [
            'isLocal'   => (bool)$isLocal,
            'API_BASE'  => ($isLocal ? 'http://localhost:8888' : 'https://ecolens.site').$prefix.'/panel-admin/web',
            'SITE_BASE' => ($isLocal ? 'http://localhost:8888' : 'https://ecolens.site').$prefix.'/sitio/web',
            'endpoints' => [],
        ];
    }

    /** Construye URL pública de activación (HTML landing) */
    private function buildActivationUrl(string $token): string {
        $env = $this->loadEnv();
        return rtrim($env['API_BASE'],'/').'/api/observador/activar?t='.$token;
    }

    /** Dirección From para el correo */
    private function mailFrom(): string {
        return Yii::$app->params['supportEmail']
            ?? Yii::$app->params['adminEmail']
            ?? 'no-reply@ecolens.site';
    }

    /** Escape simple */
    private function e(string $s): string {
        return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Envía el correo de activación usando LibreriaHelper::enviarCorreoHtml()
     */
    

    private function sendActivationEmail(Observador $m, string $token): bool {
        $url = $this->buildActivationUrl($token);
        $subject = 'Activa tu cuenta en EcoLens';

        $html = <<<HTML
    <!DOCTYPE html>
    <html lang="es">
    <head>
    <meta charset="UTF-8">
    <title>Activación de cuenta EcoLens</title>
    <style>
    body { background-color:#f8fafc; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; margin:0; padding:0; }
    .container { max-width:600px; margin:40px auto; background:#ffffff; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,0.05); padding:32px; }
    h2 { color:#0f172a; font-size:20px; margin-bottom:16px; }
    p { color:#334155; font-size:15px; line-height:1.6; }
    .btn { display:inline-block; background:#16a34a; color:#fff; padding:12px 22px; border-radius:8px; text-decoration:none; font-weight:600; margin-top:12px; }
    .footer { margin-top:32px; font-size:13px; color:#64748b; border-top:1px solid #e2e8f0; padding-top:12px; text-align:center; }
    </style>
    </head>
    <body>
    <div class="container">
    <h2>Hola {$this->e($m->obs_nombre)},</h2>
    <p>Gracias por registrarte en <strong>EcoLens</strong>. Para activar tu cuenta, haz clic en el siguiente botón:</p>
    <p><a href="{$this->e($url)}" class="btn">Activar mi cuenta</a></p>
    <p>Si el botón no funciona, copia y pega este enlace en tu navegador:</p>
    <p><a href="{$this->e($url)}">{$this->e($url)}</a></p>
    <div class="footer">
        Este enlace expira en 48 horas.<br>
        © EcoLens — Sistema de Observación de Fauna
    </div>
    </div>
    </body>
    </html>
    HTML;

        try {
            return LibreriaHelper::enviarCorreoHtml(
                $m->obs_email,
                $subject,
                $html,
                $this->mailFrom()
            );
        } catch (\Throwable $e) {
            Yii::error("No se pudo enviar email de activación: ".$e->getMessage(), __METHOD__);
            return false;
        }
    }

    /** HTML de resultado de activación */
/** HTML de resultado de activación */
private function activationHtml(string $title, string $msg, string $siteBase, bool $ok=false): string
{
    $btn = $ok
        ? '<a href="'.$this->e($siteBase).'/login" style="background:#16a34a;color:#fff;padding:10px 18px;border-radius:8px;text-decoration:none;font-weight:600;display:inline-block">Ir a iniciar sesión</a>'
        : '<a href="'.$this->e($siteBase).'/login" style="background:#475569;color:#fff;padding:10px 18px;border-radius:8px;text-decoration:none;font-weight:600;display:inline-block">Volver al login</a>';

    return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>{$this->e($title)}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body {
  font-family:-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Ubuntu, "Helvetica Neue", Arial, sans-serif;
  background:#f8fafc; margin:0; padding:0;
}
.card {
  max-width:580px; margin:10vh auto; background:#fff; border-radius:12px;
  box-shadow:0 8px 30px rgba(2,8,23,0.08); padding:32px;
}
h1 {
  margin:0 0 1rem; color:#0f172a; font-size:1.8rem;
}
p {
  color:#334155; line-height:1.6; font-size:1rem;
}
.actions {
  margin-top:1.5rem; text-align:center;
}
.footer {
  margin-top:2rem; font-size:.85rem; color:#94a3b8; text-align:center;
}
</style>
</head>
<body>
  <div class="card">
    <h1>{$this->e($title)}</h1>
    <p>{$this->e($msg)}</p>
    <div class="actions">{$btn}</div>
    <div class="footer">© EcoLens — Sistema de Observación de Fauna</div>
  </div>
</body>
</html>
HTML;
}

    // ─────────────────────────────────────────────
    // REGISTRO + envío de activación
    // ─────────────────────────────────────────────
    public function actionRegistrar()
    {
        $model = new Observador();

        // Normalizar entradas obligatorias
        $nombre  = trim((string)$this->getBodyParam('obs_nombre'));
        $email   = $this->normalizeEmail($this->getBodyParam('obs_email'));
        $usuario = $this->normalizeUsername($this->getBodyParam('obs_usuario'));

        // Mapear "avanzado" a lo que tu modelo espera
        $expIn   = (string)$this->getBodyParam('obs_experiencia', Observador::OBS_EXPERIENCIA_PRINCIPIANTE);
        $expMap  = ['avanzado' => Observador::OBS_EXPERIENCIA_EXPERTO];
        $expNorm = $expMap[mb_strtolower($expIn)] ?? $expIn;

        $model->obs_nombre      = $nombre;
        $model->obs_email       = $email;
        $model->obs_usuario     = $usuario;
        $model->obs_institucion = $this->getBodyParam('obs_institucion');
        $model->obs_experiencia = $expNorm;
        $model->obs_pais        = $this->getBodyParam('obs_pais');
        $model->obs_ciudad      = $this->getBodyParam('obs_ciudad');
        $model->obs_estado      = 'pendiente'; // forzamos pendiente
        $model->created_at      = date('Y-m-d H:i:s');
        $model->updated_at      = date('Y-m-d H:i:s');

        // contraseña
        $password = $this->getBodyParam('password', $this->getBodyParam('obs_token'));
        if (!$password)                   return ['success' => false, 'message' => 'La contraseña es obligatoria.'];
        if (strlen($password) < 8)        return ['success' => false, 'message' => 'Debe tener al menos 8 caracteres.'];
        if (!$email)                      return ['success' => false, 'message' => 'El correo es obligatorio.'];
        if (!$usuario)                    return ['success' => false, 'message' => 'El nombre de usuario es obligatorio.'];
        if (mb_strlen($usuario) < 3)      return ['success' => false, 'message' => 'El usuario debe tener al menos 3 caracteres.'];

        // Unicidad
        if (Observador::find()->where(['obs_email' => $email])->exists()) {
            return ['success' => false, 'message' => 'El correo ya está registrado.'];
        }
        if (Observador::find()->where(['obs_usuario' => $usuario])->exists()) {
            return ['success' => false, 'message' => 'El nombre de usuario ya está en uso.'];
        }

        $model->obs_token = password_hash($password, PASSWORD_BCRYPT);

        // Token de activación
        $plain  = Yii::$app->security->generateRandomString(48);
        $hash   = hash('sha256', $plain);
        $expira = date('Y-m-d H:i:s', time() + 48*3600);
        $model->obs_act_token_hash      = $hash;
        $model->obs_act_expires         = $expira;
        $model->obs_email_verificado_at = null;

        if (!$model->save()) {
            return ['success' => false, 'message' => 'No se pudo registrar.', 'errors' => $model->getErrors()];
        }

        // Enviar correo de activación usando mail() nativo
        $sent = $this->sendActivationEmail($model, $plain);
        $env  = $this->loadEnv();

        return [
            'success' => true,
            'id'      => (int)$model->obs_id,
            'message' => $sent
                ? 'Registro exitoso. Revisa tu correo para activar la cuenta.'
                : 'Registro exitoso, pero no se pudo enviar el correo de activación.',
            // En local te damos el enlace directo por si no hay SMTP
            'activation_url' => $env['isLocal'] ? $this->buildActivationUrl($plain) : null,
        ];
    }

    // ─────────────────────────────────────────────
    // ACTIVAR (landing HTML)
    // ─────────────────────────────────────────────
    public function actionActivar($t = null)
    {
        Yii::$app->response->format = Response::FORMAT_HTML;

        $env      = $this->loadEnv();
        $siteBase = rtrim($env['SITE_BASE'] ?? '/', '/');

        if (!$t || strlen($t) < 20) {
            return $this->renderContent(
                $this->activationHtml('Token inválido', 'El enlace de activación no es válido.', $siteBase)
            );
        }

        $hash = hash('sha256', $t);
        $m = Observador::find()->where(['obs_act_token_hash' => $hash])->one();
        if (!$m) {
            return $this->renderContent(
                $this->activationHtml('Token no encontrado', 'Este enlace no es válido o ya fue utilizado.', $siteBase)
            );
        }
        if (!empty($m->obs_act_expires) && strtotime($m->obs_act_expires) < time()) {
            return $this->renderContent(
                $this->activationHtml('Enlace expirado', 'El enlace ha expirado. Solicita uno nuevo desde el login.', $siteBase)
            );
        }

        $m->obs_estado               = 'activo';
        $m->obs_email_verificado_at  = date('Y-m-d H:i:s');
        $m->obs_act_token_hash       = null;
        $m->obs_act_expires          = null;
        $m->updated_at               = date('Y-m-d H:i:s');
        $m->save(false, [
            'obs_estado',
            'obs_email_verificado_at',
            'obs_act_token_hash',
            'obs_act_expires',
            'updated_at'
        ]);

        return $this->renderContent(
            $this->activationHtml('¡Cuenta activada!', 'Tu cuenta ha sido activada correctamente. Ya puedes iniciar sesión.', $siteBase, true)
        );
    }

    // ─────────────────────────────────────────────
    // REENVIAR activación (JSON)
    // ─────────────────────────────────────────────
    public function actionReenviarActivacion()
    {
        $userOrEmail = trim((string)$this->getBodyParam('username'));
        if (!$userOrEmail) return ['success' => false, 'message' => 'Debes indicar usuario o correo.'];

        $lookup = (strpos($userOrEmail, '@') !== false)
            ? ['obs_email' => $this->normalizeEmail($userOrEmail)]
            : ['obs_usuario' => $this->normalizeUsername($userOrEmail)];

        $m = Observador::find()->where($lookup)->one();
        if (!$m) return ['success' => false, 'message' => 'Usuario no encontrado.'];
        if (mb_strtolower((string)$m->obs_estado) === 'activo') {
            return ['success' => false, 'message' => 'La cuenta ya está activa.'];
        }

        // Generar nuevo token
        $plain  = Yii::$app->security->generateRandomString(48);
        $hash   = hash('sha256', $plain);
        $expira = date('Y-m-d H:i:s', time() + 48*3600);

        $m->obs_act_token_hash = $hash;
        $m->obs_act_expires    = $expira;
        $m->updated_at         = date('Y-m-d H:i:s');
        $m->save(false, ['obs_act_token_hash','obs_act_expires','updated_at']);

        $sent = $this->sendActivationEmail($m, $plain);

        return [
            'success' => (bool)$sent,
            'message' => $sent
                ? 'Te enviamos un nuevo correo de activación.'
                : 'No se pudo enviar el correo. Intenta más tarde.',
        ];
    }

    // ─────────────────────────────────────────────
    // LOGIN (correo o usuario)
    // ─────────────────────────────────────────────
    public function actionLogin()
    {
        $userOrEmail = trim((string)$this->getBodyParam('username'));
        $password    = (string)$this->getBodyParam('password');

        if (!$userOrEmail || !$password) {
            return ['success' => false, 'message' => 'Faltan credenciales.'];
        }

        $lookup = (strpos($userOrEmail, '@') !== false)
            ? ['obs_email' => $this->normalizeEmail($userOrEmail)]
            : ['obs_usuario' => $this->normalizeUsername($userOrEmail)];

        $model = Observador::find()->where($lookup)->one();
        if (!$model) {
            return ['success' => false, 'message' => 'Usuario no encontrado.'];
        }
        if (!password_verify($password, $model->obs_token)) {
            return ['success' => false, 'message' => 'Contraseña incorrecta.'];
        }

        // Exigir activación previa
        if (mb_strtolower((string)$model->obs_estado) !== 'activo') {
            return [
                'success' => false,
                'message' => 'Tu cuenta aún no está activada. Revisa tu correo o solicita reenvío.',
                'need_activation' => true,
            ];
        }

        // Guardar datos mínimos en sesión (sin Yii::$app->user)
        $s = Yii::$app->session;
        $s->set('observador_id',      $model->obs_id);
        $s->set('observador_nombre',  $model->obs_nombre);
        $s->set('observador_email',   $model->obs_email);
        $s->set('observador_usuario', $model->obs_usuario ?? '');

        return [
            'success' => true,
            'id'      => (int)$model->obs_id,
            'nombre'  => (string)$model->obs_nombre,
            'email'   => (string)$model->obs_email,
            'message' => 'Inicio de sesión exitoso'
        ];
    }

    // ─────────────────────────────────────────────
    // WHOAMI / LOGOUT
    // ─────────────────────────────────────────────
    public function actionWhoami()
    {
        $sid = Yii::$app->session->get('observador_id');
        if (!$sid) return ['authenticated' => false];

        return [
            'authenticated' => true,
            'id'      => (int)$sid,
            'nombre'  => (string)Yii::$app->session->get('observador_nombre', ''),
            'email'   => (string)Yii::$app->session->get('observador_email', ''),
            'usuario' => (string)Yii::$app->session->get('observador_usuario', ''),
        ];
    }

    public function actionLogout()
    {
        Yii::$app->session->destroy();
        return ['success' => true, 'message' => 'Sesión cerrada.'];
    }

    // ─────────────────────────────────────────────
    // ACTUALIZAR DATOS (con chequeo de colisiones)
    // ─────────────────────────────────────────────
    public function actionActualizar($id)
    {
        $model = Observador::findOne($id);
        if (!$model) throw new BadRequestHttpException("No se encontró el observador con ID $id");

        $data = [];
        $req  = Yii::$app->request;
        if (stripos((string)$req->contentType, 'application/json') !== false) {
            $data = json_decode($req->getRawBody(), true) ?: [];
        } else {
            $data = $req->post();
        }

        // Normalizar si vienen en payload y validar unicidad
        if (array_key_exists('obs_email', $data)) {
            $data['obs_email'] = $this->normalizeEmail($data['obs_email']);
            if ($data['obs_email'] && $data['obs_email'] !== $model->obs_email) {
                $exists = Observador::find()
                    ->where(['obs_email' => $data['obs_email']])
                    ->andWhere(['<>','obs_id',$model->obs_id])
                    ->exists();
                if ($exists) return ['success' => false, 'message' => 'El correo ya está registrado.'];
            }
        }
        if (array_key_exists('obs_usuario', $data)) {
            $data['obs_usuario'] = $this->normalizeUsername($data['obs_usuario']);
            if (!$data['obs_usuario']) {
                return ['success' => false, 'message' => 'El nombre de usuario no puede quedar vacío.'];
            }
            if ($data['obs_usuario'] !== $model->obs_usuario) {
                $exists = Observador::find()
                    ->where(['obs_usuario' => $data['obs_usuario']])
                    ->andWhere(['<>','obs_id',$model->obs_id])
                    ->exists();
                if ($exists) return ['success' => false, 'message' => 'El nombre de usuario ya está en uso.'];
            }
        }
        if (array_key_exists('obs_experiencia', $data)) {
            $map = ['avanzado' => Observador::OBS_EXPERIENCIA_EXPERTO];
            $data['obs_experiencia'] = $map[mb_strtolower((string)$data['obs_experiencia'])] ?? $data['obs_experiencia'];
        }

        foreach (['obs_nombre','obs_usuario','obs_institucion','obs_experiencia','obs_pais','obs_ciudad','obs_bio','obs_email'] as $f) {
            if (array_key_exists($f, $data)) $model->$f = $data[$f];
        }
        $model->updated_at = date('Y-m-d H:i:s');

        if ($model->save()) {
            // refrescar sesión si el que edita es el dueño
            if (Yii::$app->session->get('observador_id') == $model->obs_id) {
                Yii::$app->session->set('observador_nombre', $model->obs_nombre);
                Yii::$app->session->set('observador_usuario', $model->obs_usuario ?? '');
                Yii::$app->session->set('observador_email',   $model->obs_email ?? '');
            }
            return ['success' => true, 'message' => 'Datos actualizados correctamente.'];
        }
        return ['success' => false, 'message' => 'Error al actualizar.', 'errors' => $model->getErrors()];
    }

    // ─────────────────────────────────────────────
    // SUBIR FOTO DE PERFIL
    // ─────────────────────────────────────────────
    public function actionSubirFoto($id)
    {
        $model = Observador::findOne($id);
        if (!$model) return ['success' => false, 'message' => "No se encontró el observador con ID $id"];

        $archivo = UploadedFile::getInstanceByName('foto');
        if (!$archivo) return ['success' => false, 'message' => 'No se recibió ningún archivo.'];

        $rutaFisica = Yii::getAlias('@app/../recursos/uploads/observadores/');
        if (!is_dir($rutaFisica)) @mkdir($rutaFisica, 0775, true);

        // limpiar anteriores
        foreach (['jpg','jpeg','png','gif','webp'] as $ext) {
            $ant = $rutaFisica . $model->obs_id . '.' . $ext;
            if (file_exists($ant)) @unlink($ant);
        }

        $nombreArchivo = $model->obs_id . '.' . $archivo->extension;
        $rutaCompleta  = $rutaFisica . $nombreArchivo;

        if ($archivo->saveAs($rutaCompleta)) {
            $model->obs_foto   = 'observadores/' . $nombreArchivo; // ruta relativa
            $model->updated_at = date('Y-m-d H:i:s');
            $model->save(false, ['obs_foto','updated_at']);

            $urlPublica = Yii::getAlias('@web') . "/../../recursos/uploads/observadores/" . $nombreArchivo;
            return ['success' => true, 'message' => 'Foto actualizada correctamente.', 'url' => $urlPublica];
        }
        return ['success' => false, 'message' => 'No se pudo guardar la imagen.'];
    }

    // ─────────────────────────────────────────────
    // CAMBIAR CONTRASEÑA
    // ─────────────────────────────────────────────
    public function actionCambiarPassword()
    {
        $data   = json_decode(Yii::$app->request->getRawBody(), true) ?: [];
        $id     = (int)($data['id'] ?? 0);
        $actual = trim($data['actual'] ?? '');
        $nueva  = trim($data['nueva'] ?? '');

        if (!$id || !$actual || !$nueva) return ['success' => false, 'message' => 'Datos incompletos.'];

        $model = Observador::findOne($id);
        if (!$model) return ['success' => false, 'message' => 'Observador no encontrado.'];
        if (!password_verify($actual, $model->obs_token)) return ['success' => false, 'message' => 'Contraseña actual incorrecta.'];
        if (strlen($nueva) < 8) return ['success' => false, 'message' => 'La nueva contraseña debe tener al menos 8 caracteres.'];

        $model->obs_token   = password_hash($nueva, PASSWORD_BCRYPT);
        $model->updated_at  = date('Y-m-d H:i:s');
        $model->save(false, ['obs_token','updated_at']);

        return ['success' => true, 'message' => 'Contraseña actualizada correctamente.'];
    }

    // ─────────────────────────────────────────────
    // ELIMINAR CUENTA
    // ─────────────────────────────────────────────
    public function actionEliminar()
    {
        $data = json_decode(Yii::$app->request->getRawBody(), true) ?: [];
        $id   = (int)($data['id'] ?? 0);

        $model = Observador::findOne($id);
        if (!$model) return ['success' => false, 'message' => 'Observador no encontrado.'];

        if (Yii::$app->session->get('observador_id') != $id)
            return ['success' => false, 'message' => 'No autorizado.'];

        if ($model->delete() !== false) {
            Yii::$app->session->destroy();
            return ['success' => true, 'message' => 'Cuenta eliminada correctamente.'];
        }
        return ['success' => false, 'message' => 'No se pudo eliminar la cuenta.'];
    }

    // ─────────────────────────────────────────────
    // DETALLE
    // ─────────────────────────────────────────────
    public function actionDetalle($id)
    {
        $model = Observador::findOne($id);
        if (!$model) return ['success' => false, 'message' => "No se encontró el observador con ID $id"];

        return [
            'success' => true,
            'data' => [
                'obs_id'          => (int)$model->obs_id,
                'obs_nombre'      => (string)$model->obs_nombre,
                'obs_email'       => (string)$model->obs_email,
                'obs_usuario'     => (string)$model->obs_usuario,
                'obs_institucion' => (string)$model->obs_institucion,
                'obs_experiencia' => $model->obs_experiencia,
                'obs_pais'        => (string)$model->obs_pais,
                'obs_ciudad'      => (string)$model->obs_ciudad,
                'obs_estado'      => (string)$model->obs_estado,
                'obs_foto'        => $model->obs_foto ? "../../recursos/uploads/" . $model->obs_foto : null,
                'created_at'      => $model->created_at,
                'updated_at'      => $model->updated_at,
            ]
        ];
    }

    // ─────────────────────────────────────────────
    // DEBUG SESIÓN
    // ─────────────────────────────────────────────
    public function actionDebugSession()
    {
        $s = Yii::$app->session;
        if (!$s->isActive) $s->open();

        $sessionDump = [];
        foreach ($s as $k => $v) $sessionDump[$k] = $v;

        return [
            'php_session_id' => session_id(),
            'yii_session' => $sessionDump,
            'observador' => [
                'id'      => $s->get('observador_id'),
                'nombre'  => $s->get('observador_nombre'),
                'email'   => $s->get('observador_email'),
                'usuario' => $s->get('observador_usuario'),
            ],
            'request' => [
                'origin'  => Yii::$app->request->headers->get('Origin') ?? (Yii::$app->request->hostInfo ?? null),
                'cookies' => Yii::$app->request->cookies->toArray(),
                'headers' => Yii::$app->request->headers->toArray(),
            ],
        ];
    }
}