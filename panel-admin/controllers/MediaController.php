<?php

namespace app\controllers;

use app\models\Media;
use app\models\MediaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\helpers\LibreriaHelper;
use yii\web\UploadedFile;
use app\models\User;
use Yii;
use yii\web\Response; 

/**
 * MediaController implements the CRUD actions for Media model.
 */
class MediaController extends Controller
{
    /* ─────────────────────────── ACCESO ─────────────────────────── */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'only'  => ['index','view','create','update','delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            $usuario = Yii::$app->user->identity->usu_id;
                            return User::checkRoleByUserId($usuario,[1,2,3]);
                        },
                    ],
                ],
            ],
        ];
    }

    /* ─────────────────────────── LISTA ─────────────────────────── */
    public function actionIndex()
    {
        $searchModel  = new MediaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
    
        // EXCLUIR med_tipo = 'tinymce'
        $dataProvider->query->andWhere(['<>', 'med_tipo', 'tinymce']);
    
        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    

    /**
     * Lista únicamente las imágenes subidas por TinyMCE
     */
    public function actionTinymce()
    {
        $searchModel  = new MediaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        // Solo tinymce
        $dataProvider->query->andWhere(['med_tipo' => 'tinymce']);

        return $this->render('tinymce', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /* ─────────────────────────── VER ─────────────────────────── */
    public function actionView($med_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($med_id),
        ]);
    }

    /* ─────────────────────────── CREAR ─────────────────────────── */
    public function actionCreate()
    {
        $msg   = null;                 // ← inicializamos
        $model = new Media();

        if ($this->request->isPost && $model->load($this->request->post())) {

            /* Normalizamos med_entidad */
            if ($model->med_tipo === 'entidad' && $model->med_entidad) {
                $model->med_entidad = strtolower($model->med_entidad);
            }

            /* Manejo de archivo */
            $model->med_ruta = UploadedFile::getInstance($model,'med_ruta');
            if ($model->med_ruta) {
                $rel = $this->subirFoto($model);        // devuelve ruta relativa o ''
                if (!$rel) {
                    $msg = 'Error al guardar la imagen.';
                } else {
                    $model->med_ruta = $rel;
                }
            } else {
                $msg = 'Debes seleccionar una imagen.';
            }

            if (!$msg && $model->save(false)) {         // false = sin validar de nuevo
                Yii::$app->session->setFlash('success','Registro creado.');
                return $this->redirect(['view','med_id'=>$model->med_id]);
            }
        }

        return $this->render('create', compact('model','msg'));
    }

    /* ─────────────────────────── ACTUALIZAR ─────────────────────────── */
    public function actionUpdate($med_id)
    {
        $model       = $this->findModel($med_id);
        $msg         = null;
        $rutaAnterior= $model->med_ruta;   // por si se reemplaza

        if ($this->request->isPost && $model->load($this->request->post())) {

            if ($model->med_tipo === 'entidad' && $model->med_entidad) {
                $model->med_entidad = strtolower($model->med_entidad);
            }

            $file = UploadedFile::getInstance($model,'med_ruta');
            if ($file) {
                // eliminamos archivo previo si existía
                $absPrev = dirname(Yii::getAlias('@webroot')).'/recursos/uploads/'.$rutaAnterior;
                if (is_file($absPrev)) { @unlink($absPrev); }

                $model->med_ruta = $file;               // asignamos para subirFoto()
                $rel            = $this->subirFoto($model);
                if ($rel) {
                    $model->med_ruta = $rel;
                } else {
                    $msg = 'Error al reemplazar la imagen.';
                }
            } else {
                // no se subió nada -> dejamos la ruta anterior
                $model->med_ruta = $rutaAnterior;
            }

            if (!$msg && $model->save(false)) {
                Yii::$app->session->setFlash('success','Registro actualizado.');
                return $this->redirect(['view','med_id'=>$model->med_id]);
            }
        }

        return $this->render('update', compact('model','msg'));
    }

    /* ─────────────────────────── ELIMINAR ─────────────────────────── */
    public function actionDelete($med_id)
    {
        $this->findModel($med_id)->delete();
        return $this->redirect(['index']);
    }

    /* ─────────────────────────── UTIL ─────────────────────────── */
    protected function findModel($med_id)
    {
        if (($model = Media::findOne(['med_id'=>$med_id])) !== null) { return $model; }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Sube la imagen «sin_imagen» al directorio correcto y devuelve la ruta relativa
     */
 protected function subirFoto(Media $model): string
{
    

    /** @var UploadedFile $uploadedFile */
    $uploadedFile = $model->med_ruta;

    if (!$uploadedFile instanceof \yii\web\UploadedFile) {
        return '';
    }

    $tipo    = $model->med_tipo;
    $entidad = strtolower($model->med_entidad ?? '');

    if ($tipo === 'entidad' && !$entidad) {
        return '';
    }

    $rootBase   = dirname(dirname(Yii::getAlias('@webroot')));
    $basePath   = $rootBase . '/recursos/uploads/default';
    $tipoFolder = ($tipo === 'entidad') ? "entidad/$entidad" : 'site';
    $destDir    = "$basePath/$tipoFolder";

    if (!is_dir($destDir) && !mkdir($destDir, 0755, true)) {
        return '';
    }

    // ✅ Usar med_nombre como base
    $nombreBase  = $model->med_nombre ?: 'imagen';
    $slug        = preg_replace('/[^a-z0-9]+/', '_', strtolower($nombreBase));
    $ext         = strtolower($uploadedFile->extension);
    $nombreFinal = "{$slug}.{$ext}";
    $rutaFisica  = "$destDir/$nombreFinal";

    // Si ya existe, renombrar
    if (is_file($rutaFisica)) {
        $nombreFinal = "{$slug}_" . time() . ".{$ext}";
        $rutaFisica  = "$destDir/$nombreFinal";
    }

    if ($uploadedFile->saveAs($rutaFisica)) {
        LibreriaHelper::resizeImage($rutaFisica, $rutaFisica, 600, 600);
        return "default/$tipoFolder/$nombreFinal";
    }

    return '';
}

    /**
 * Subida desde TinyMCE: guarda TODAS las imágenes en /recursos/uploads/tinymce/
 */


 public function actionUpload($entidad, $registro)
{
    Yii::$app->response->format = Response::FORMAT_JSON;

    try {
        if (empty($_FILES['file'])) {
            throw new \Exception('No se recibió archivo');
        }

        // 1) siguiente orden
        $next  = (new \yii\db\Query())
                    ->from('media')
                    ->where(['med_tipo'=>'tinymce'])
                    ->max('med_orden');
        $orden = ((int)$next) + 1;

        // 2) nombre correlativo
        $file = $_FILES['file'];
        $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $seq  = str_pad($orden, 4, '0', STR_PAD_LEFT);
        $name = "IMG-{$seq}.{$ext}";

        // 3) carpeta física: recursos/uploads/tinymce
        // @webroot  ≅ /…/rogeliomunoz_final/panel-admin/web
        $basePath = Yii::getAlias('@webroot/../../recursos/uploads/tinymce/');
        if (!is_dir($basePath) && !mkdir($basePath, 0755, true)) {
            throw new \Exception("No se pudo crear carpeta: {$basePath}");
        }

        // 4) mover archivo
        $tmp  = $file['tmp_name'];
        $dest = $basePath . $name;
        if (!move_uploaded_file($tmp, $dest)) {
            throw new \Exception("Error al mover archivo a {$dest}");
        }

        // 5) redimensionar / corregir
        $this->redimensionarYCorregir($dest);

        // 6) grabar en BD
        $media = new Media();
        $media->med_nombre      = $name;
        $media->med_ruta        = "tinymce/{$name}";
        $media->med_descripcion = 'Subido desde TinyMCE';
        $media->med_entidad     = $entidad;
        $media->med_tipo        = 'tinymce';
        $media->med_registro    = $registro;
        $media->med_orden       = $orden;
        if (!$media->save()) {
            throw new \Exception('Error guardando media: '.json_encode($media->errors));
        }

        // 7) construir URL pública
        // request->baseUrl ≅ "/SITIOS/rogeliomunoz_final/panel-admin/web"
        $publicBase = preg_replace('#/panel-admin/web#','', Yii::$app->request->baseUrl);
        $url = Yii::$app->request->hostInfo 
             . $publicBase
             . '/recursos/uploads/tinymce/'
             . $name;

        return [
            'location' => $url,
            'media_id' => $media->med_id,
        ];

    } catch (\Exception $e) {
        Yii::$app->response->statusCode = 500;
        Yii::error($e->getMessage(), __METHOD__);
        return ['error' => $e->getMessage()];
    }
}




/**
 * Redimensiona y corrige orientación EXIF de una imagen JPEG/PNG/GIF/BMP
 *
 * @param string $archivo   Ruta absoluta al archivo en disco
 * @param int    $maxWidth  Ancho máximo deseado
 * @param int    $maxHeight Alto máximo deseado
 * @return bool  Devuelve true si tuvo éxito, false en caso contrario
 */
private function redimensionarYCorregir(string $archivo, int $maxWidth = 800, int $maxHeight = 800): bool
{
    // 1) Determinar extensión
    $ext = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));

    // 2) Cargar imagen en memoria según tipo
    switch ($ext) {
        case 'jpg':
        case 'jpeg':
            $img = @imagecreatefromjpeg($archivo);
            break;
        case 'png':
            $img = @imagecreatefrompng($archivo);
            break;
        case 'gif':
            $img = @imagecreatefromgif($archivo);
            break;
        case 'bmp':
            $img = @imagecreatefromwbmp($archivo);
            break;
        default:
            return false; // formato no soportado
    }
    if (!$img) {
        return false;
    }

    // 3) Corregir orientación EXIF (solo JPEG)
    if (in_array($ext, ['jpg','jpeg'], true)) {
        $exif = @exif_read_data($archivo);
        if (!empty($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 3:
                    $img = imagerotate($img, 180, 0);
                    break;
                case 6:
                    $img = imagerotate($img, -90, 0);
                    break;
                case 8:
                    $img = imagerotate($img, 90, 0);
                    break;
            }
        }
    }

    // 4) Obtener dimensiones originales
    $srcW = imagesx($img);
    $srcH = imagesy($img);
    $srcRatio = $srcW / $srcH;
    $dstRatio = $maxWidth / $maxHeight;

    // 5) Calcular nuevas dimensiones manteniendo proporción
    if ($srcRatio > $dstRatio) {
        $newW = $maxWidth;
        $newH = (int)($maxWidth / $srcRatio);
    } else {
        $newH = $maxHeight;
        $newW = (int)($maxHeight * $srcRatio);
    }

    // 6) Crear lienzo destino
    $dst = imagecreatetruecolor($newW, $newH);

    // 7) Conservar transparencia en PNG y GIF
    if (in_array($ext, ['png','gif'], true)) {
        imagecolortransparent($dst, imagecolorallocatealpha($dst, 0, 0, 0, 127));
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
    }

    // 8) Copiar y redimensionar
    imagecopyresampled($dst, $img, 0, 0, 0, 0, $newW, $newH, $srcW, $srcH);

    // 9) Sobrescribir archivo según tipo
    switch ($ext) {
        case 'jpg':
        case 'jpeg':
            imagejpeg($dst, $archivo, 75);
            break;
        case 'png':
            imagepng($dst, $archivo);
            break;
        case 'gif':
            imagegif($dst, $archivo);
            break;
        case 'bmp':
            imagewbmp($dst, $archivo);
            break;
    }

    // 10) Liberar memoria
    imagedestroy($img);
    imagedestroy($dst);

    return true;
}


/**
 * Listado JSON de todas las imágenes subidas vía TinyMCE
 * Devuelve un array de { text, value } donde:
 *  - text  = nombre de la imagen
 *  - value = URL pública completa de la imagen
 */
public function actionBrowse_old()
{
    Yii::$app->response->format = Response::FORMAT_JSON;

    // Recupera todas las entradas tipo 'tinymce'
    $rows = Media::find()
        ->select(['med_id','med_nombre','med_ruta'])
        ->where(['med_tipo' => 'tinymce'])
        ->orderBy(['med_orden' => SORT_ASC])
        ->asArray()
        ->all();

    $host    = Yii::$app->request->hostInfo;
    // baseUrl apunta a /panel-admin/web o similar, lo limpiamos si es necesario
    $baseUrl = preg_replace('#/panel-admin(/web)?#','', Yii::$app->request->baseUrl);

    return array_map(function($r) use($host, $baseUrl) {
        $url = "{$host}{$baseUrl}/recursos/uploads/{$r['med_ruta']}";
        return [
            'text'  => $r['med_nombre'],
            'value' => $url,
        ];
    }, $rows);
}



public function actionBrowse($entidad, $registro)
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    $basePath = Yii::getAlias('@webroot') . '/recursos/uploads/' . $entidad . '/' . $registro;
    $baseUrl  = Yii::getAlias('@web') . '/recursos/uploads/' . $entidad . '/' . $registro;

    $lista = [];
    if (is_dir($basePath)) {
        $archivos = FileHelper::findFiles($basePath, [
            'only' => ['*.jpg', '*.jpeg', '*.png', '*.gif', '*.webp']
        ]);
        foreach ($archivos as $rutaCompleta) {
            $nombreArchivo = basename($rutaCompleta);
            $lista[] = [
                'value' => $baseUrl . '/' . $nombreArchivo,
                'text'  => $nombreArchivo,
            ];
        }
    }

    return $lista;
}

public function beforeAction($action)
{
    // Deshabilita CSRF para 'actionUpload' Y 'actionProcessEditedImage'
    if ($action->id === 'upload' || $action->id === 'process-edited-image') {
        Yii::info('Deshabilitando validación CSRF para ' . $action->id, __METHOD__);
        Yii::$app->request->enableCsrfValidation = false;
    }
    return parent::beforeAction($action);
}



}
