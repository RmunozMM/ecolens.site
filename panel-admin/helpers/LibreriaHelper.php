<?php
namespace app\helpers;

use Yii;
use yii\base\Exception;
use yii\web\UploadedFile;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailerException;

/**
 * Clase de utilidades generales: rutas, imágenes, slugs, moneda, fechas, etc.
 */
class LibreriaHelper
{
    public static function getPanelAdminUrl()
    {
        $ruta = $_SERVER['REQUEST_URI'] ?? '';
        $ruta = explode("sitio/web", $ruta);
        return $ruta[0] . "panel-admin/";
    }

    public static function getRecursos()
    {
        $ruta = $_SERVER['REQUEST_URI'] ?? '';
        $ruta = explode("panel-admin/web", $ruta);
        return $ruta[0] . "recursos/";
    }

    public static function obtenerNombreMes($mes)
    {
        $meses = [
            'January'=>'enero','February'=>'febrero','March'=>'marzo','April'=>'abril',
            'May'=>'mayo','June'=>'junio','July'=>'julio','August'=>'agosto',
            'September'=>'septiembre','October'=>'octubre','November'=>'noviembre','December'=>'diciembre',
        ];
        return $meses[$mes] ?? $mes;
    }

    public static function obtenerNombreDia($dia)
    {
        $dias = [
            'Monday'=>'lunes','Tuesday'=>'martes','Wednesday'=>'miércoles','Thursday'=>'jueves',
            'Friday'=>'viernes','Saturday'=>'sábado','Sunday'=>'domingo',
        ];
        return $dias[$dia] ?? $dia;
    }

    public static function generateSlug($string)
    {
        $string = strtolower($string);
        $string = preg_replace('/[áàâãªä]/ui', 'a', $string);
        $string = preg_replace('/[éèêë]/ui', 'e', $string);
        $string = preg_replace('/[íìîï]/ui', 'i', $string);
        $string = preg_replace('/[óòôõºö]/ui', 'o', $string);
        $string = preg_replace('/[úùûü]/ui', 'u', $string);
        $string = preg_replace('/[ñ]/ui', 'n', $string);
        $string = preg_replace('/[^a-z0-9]+/', '-', $string);
        $string = preg_replace('/-+/', '-', $string);
        return strtolower(trim($string, '-'));
    }

    public static function formatoMoneda($numero)
    {
        return number_format($numero, 2, ',', '.');
    }

    public static function resizeImage($ruta, $nombre, $maxWidth = null, $maxHeight = null)
    {
        try {
            $imagen = @imagecreatefromjpeg($nombre);
            if (!$imagen) throw new Exception('No se pudo cargar la imagen.');

            $x = imagesx($imagen);
            $y = imagesy($imagen);
            $srcAspect = $x / $y;

            if ($maxWidth && $maxHeight) {
                $dstAspect = $maxWidth / $maxHeight;
                if ($srcAspect > $dstAspect) {
                    $newWidth = $maxWidth;
                    $newHeight = $maxWidth / $srcAspect;
                } else {
                    $newHeight = $maxHeight;
                    $newWidth = $maxHeight * $srcAspect;
                }
            } elseif ($maxWidth) {
                $newWidth = $maxWidth;
                $newHeight = $y * ($maxWidth / $x);
            } elseif ($maxHeight) {
                $newHeight = $maxHeight;
                $newWidth = $x * ($maxHeight / $y);
            } else {
                $newWidth = $x;
                $newHeight = $y;
            }

            $gd_d = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($gd_d, $imagen, 0, 0, 0, 0, $newWidth, $newHeight, $x, $y);
            if (!@imagejpeg($gd_d, $nombre, 85)) throw new Exception('Error al guardar la imagen redimensionada.');

            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function subirFoto($model, $campoImagen, $directorio, $uploadedFileInstance = null)
    {
        $imagen = $uploadedFileInstance ?: UploadedFile::getInstance($model, $campoImagen);
        if (!$imagen) return null;

        $idModelo = $model->getPrimaryKey();
        if (!$idModelo) return null;

        $rutaDirectorio = "../../recursos/uploads/{$directorio}/";
        if (!is_dir($rutaDirectorio)) @mkdir($rutaDirectorio, 0775, true);

        $extsPosibles = ['jpg','jpeg','png','gif','webp','heic','heif','avif','jxl'];
        foreach ($extsPosibles as $extAntiguo) {
            $rutaAntigua = $rutaDirectorio . $idModelo . '.' . $extAntiguo;
            if (file_exists($rutaAntigua)) @unlink($rutaAntigua);
        }

        $ext  = strtolower($imagen->extension ?: '');
        $mime = @mime_content_type($imagen->tempName) ?: '';
        $formatoRaro = in_array($ext, ['heic','heif','avif','jxl'], true)
            || in_array($mime, ['image/heic','image/heif','image/avif','image/jxl','image/x-heic','image/x-heif'], true);

        if ($formatoRaro && class_exists('\\Imagick')) {
            try {
                $im = new \Imagick($imagen->tempName);
                $im->setImageFormat('jpg');
                $tmpNuevo = sys_get_temp_dir() . '/' . uniqid('conv_', true) . '.jpg';
                $im->writeImage($tmpNuevo);
                $imagen->tempName = $tmpNuevo;
                $ext = 'jpg';
            } catch (\Throwable $e) {}
        }

        if ($ext === '' || preg_match('/[^a-z0-9]/', $ext)) $ext = 'jpg';

        $nombreArchivo = $idModelo . "." . $ext;
        $rutaCompleta  = $rutaDirectorio . $nombreArchivo;

        if ($imagen->saveAs($rutaCompleta)) {
            if (in_array($ext, ['jpg','jpeg'])) {
                $exif = @exif_read_data($rutaCompleta);
                if (!empty($exif['Orientation'])) {
                    $image = imagecreatefromjpeg($rutaCompleta);
                    switch ($exif['Orientation']) {
                        case 3: $image = imagerotate($image, 180, 0); break;
                        case 6: $image = imagerotate($image, -90, 0); break;
                        case 8: $image = imagerotate($image, 90, 0); break;
                    }
                    imagejpeg($image, $rutaCompleta, 85);
                    imagedestroy($image);
                }
            }

            $rutaDB = $directorio . "/" . $nombreArchivo;
            if ($model->canSetProperty($campoImagen)) $model->$campoImagen = $rutaDB;
            return $rutaDB;
        }

        return null;
    }

    /**
     * Envía un correo HTML usando PHPMailer + Brevo (SMTP) con soporte completo UTF-8
     */
    public static function enviarCorreoHtml($to, $subject, $htmlBody, $from = null, $bcc = null)
    {

        // ✅ Forzamos la zona horaria correcta (Chile)
        date_default_timezone_set('America/Santiago');
        

        $to = trim($to);
        $fecha = date('Y-m-d H:i:s');
        $basePath = dirname(Yii::getAlias('@app')) . '/logs';
        if (!is_dir($basePath)) @mkdir($basePath, 0775, true);
        $logFile = $basePath . '/ecolens_correos.log';

        if ($to === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
            file_put_contents($logFile, "[$fecha] ERROR: Email destino inválido: {$to}\n", FILE_APPEND);
            return false;
        }

        $mail = new PHPMailer(true);
        $resultado = false;
        $errorMsg = '';

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp-relay.brevo.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = '9a1b8c001@smtp-brevo.com';
            $mail->Password = 'CLAVE_SUPER_SECRETA_REAL';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // 💡 Forzar codificación correcta
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            $mail->setFrom($from ?: 'no-reply@ecolens.site', 'EcoLens');
            $mail->addAddress($to);
            if ($bcc && filter_var($bcc, FILTER_VALIDATE_EMAIL)) $mail->addBCC($bcc);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $htmlBody;
            $mail->AltBody = strip_tags($htmlBody); // versión de texto plano

            $mail->send();
            $resultado = true;
        } catch (MailerException $e) {
            $resultado = false;
            $errorMsg = $e->getMessage();
        }

        $hash = substr(sha1($subject . $to . $fecha), 0, 12);
        $status = $resultado ? 'OK' : 'FAIL';
        $msg = "[$fecha][$status][$hash] Para: {$to} | Asunto: {$subject}";
        if (!$resultado && $errorMsg) $msg .= " | Error: {$errorMsg}";
        $msg .= "\n";
        file_put_contents($logFile, $msg, FILE_APPEND | LOCK_EX);

        return $resultado;
    }
}