<?php
namespace app\modules\api\controllers;

use yii\rest\Controller;
use yii\filters\Cors;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use yii\web\BadRequestHttpException;
use Yii;
use app\models\Asunto;
use app\models\Correo;
use app\helpers\LibreriaHelper;

class ContactoController extends Controller
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        $b = parent::behaviors();

        // 1) Permitir CORS para GET/POST/OPTIONS
        $b['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Allow-Methods' => ['GET', 'POST', 'OPTIONS'],
                'Access-Control-Allow-Headers' => ['*'],
            ],
        ];

        // 2) Forzar formato JSON para clientes que lo pidan explícitamente
        $b['contentNegotiator'] = [
            'class'   => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];

        return $b;
    }

    /**
     * POST /api/contacto
     * Registra el mensaje y envía correo de confirmación vía Brevo (PHPMailer)
     */
    public function actionContacto()
    {
        $request = Yii::$app->getRequest();

        // 1) Extraer datos del body o formulario
        $body = $request->getBodyParams();
        $correo = new Correo();
        $correo->cor_nombre         = $body['cor_nombre']   ?? null;
        $correo->cor_correo         = $body['cor_correo']   ?? null;
        $correo->cor_asunto         = $body['cor_asunto']   ?? null;
        $correo->cor_mensaje        = $body['cor_mensaje']  ?? null;
        $correo->cor_fecha_consulta = date('Y-m-d H:i:s');

        // 2) Validar y guardar
        if (!$correo->validate()) {
            $errores = json_encode($correo->errors, JSON_UNESCAPED_UNICODE);
            if ($request->getHeaders()->get('Accept') === 'application/json') {
                throw new BadRequestHttpException("Validación fallida: $errores");
            }
            Yii::$app->session->setFlash('error', "Validación fallida: $errores");
            return $this->redirect($request->getReferrer());
        }

        if (!$correo->save(false)) {
            if ($request->getHeaders()->get('Accept') === 'application/json') {
                return ['success' => false, 'error' => 'No se pudo guardar el mensaje en la base de datos.'];
            }
            Yii::$app->session->setFlash('error', 'No se pudo guardar el mensaje.');
            return $this->redirect($request->getReferrer());
        }

        // 3) Enviar correo de confirmación con PHPMailer + Brevo
        $htmlBody = "
            <p>Hola <strong>{$correo->cor_nombre}</strong>,</p>
            <p>Gracias por contactarte con <strong>EcoLens</strong>. Hemos recibido tu mensaje:</p>
            <blockquote>{$correo->cor_mensaje}</blockquote>
            <p>Nos pondremos en contacto contigo a la brevedad.</p>
            <p style='margin-top:20px;font-size:12px;color:#888;'>Mensaje generado automáticamente el ".date('d/m/Y H:i').".</p>
        ";

        $ok = LibreriaHelper::enviarCorreoHtml(
            $correo->cor_correo,
            "Hemos recibido tu mensaje: {$correo->cor_asunto}",
            $htmlBody,
            'no-reply@ecolens.site',
            Yii::$app->params['email_admin'] ?? null
        );

        // 4) Responder según tipo de solicitud
        $acceptHeader = $request->getHeaders()->get('Accept');
        if (strpos($acceptHeader, 'application/json') !== false) {
            return [
                'success' => true,
                'message' => 'Mensaje registrado y correo ' . ($ok ? 'enviado correctamente.' : 'registrado (falló envío de correo).')
            ];
        }

        Yii::$app->session->setFlash(
            $ok ? 'success' : 'warning',
            $ok
                ? 'Tu mensaje ha sido enviado correctamente.'
                : 'El mensaje se registró, pero no se pudo enviar el correo de confirmación.'
        );

        return $this->redirect($request->getReferrer());
    }

    /**
     * GET /api/contacto/asuntos
     * Devuelve un listado de asuntos activos.
     */
    public function actionAsuntos()
    {
        $asuntos = Asunto::find()
            ->select(['asu_id', 'asu_nombre'])
            ->where(['asu_publicado' => 'SI'])
            ->orderBy(['asu_nombre' => SORT_ASC])
            ->asArray()
            ->all();

        return $asuntos;
    }
}