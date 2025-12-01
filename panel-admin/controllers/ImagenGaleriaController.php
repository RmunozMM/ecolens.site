<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use app\models\ImagenesGaleria;
use app\models\ImagenGaleriaSearch;
use app\models\Galerias;
use app\models\User;

class ImagenGaleriaController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only'  => ['index','view','create','update','delete','upload','browse'],
                'rules' => [
                    [
                        'allow'   => true,
                        'roles'   => ['@'],
                        'actions' => ['index','view','create','update','delete','upload','browse'],
                        'matchCallback' => function() {
                            return User::checkRoleByUserId(Yii::$app->user->id, [1,2,3]);
                        },
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel  = new ImagenGaleriaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($img_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($img_id),
        ]);
    }

    public function actionCreate($gal_id)
    {
        $galeria   = Galerias::findOne($gal_id);
        $gal_titulo= $galeria->gal_titulo ?? '';
        $model     = new ImagenesGaleria();
        $msg       = '';

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $img_id = $model->img_id;
                $ruta   = $this->subirFoto($model, $img_id);
                if ($ruta !== '') {
                    $model->updateAttributes(['img_ruta' => $ruta]);
                }
                return $this->redirect(['galeria/view', 'gal_id' => $model->img_gal_id]);
            }
            $msg = 'Error al crear registro.';
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model'  => $model,
            'msg'    => $msg,
            'titulo' => $gal_titulo,
        ]);
    }

    public function actionUpdate($img_id)
    {
        $model = $this->findModel($img_id);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'img_id' => $model->img_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($img_id)
    {
        $model = $this->findModel($img_id);
        $path  = Yii::getAlias('@app/../recursos/uploads/' . dirname($model->img_ruta) . '/');
        $file  = $path . basename($model->img_ruta);

        if (is_file($file)) {
            @unlink($file);
        }

        $model->delete();
        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    }

    /**
     * Redimensiona y corrige orientación
     */
    private function redimensionarYCorregir($archivo, $maxWidth = 800, $maxHeight = 800)
    {
        $ext = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                $img = @imagecreatefromjpeg($archivo); break;
            case 'png':
                $img = @imagecreatefrompng($archivo); break;
            case 'gif':
                $img = @imagecreatefromgif($archivo); break;
            case 'bmp':
                $img = @imagecreatefromwbmp($archivo); break;
            default:
                return false;
        }
        if (!$img) return false;

        // Corregir orientación EXIF (JPEG)
        if (in_array($ext, ['jpg','jpeg'])) {
            $exif = @exif_read_data($archivo);
            if (!empty($exif['Orientation'])) {
                switch ($exif['Orientation']) {
                    case 3: $img = imagerotate($img, 180, 0); break;
                    case 6: $img = imagerotate($img, -90, 0); break;
                    case 8: $img = imagerotate($img, 90, 0); break;
                }
            }
        }

        $w = imagesx($img); $h = imagesy($img);
        $ratioSrc = $w/$h;
        $ratioDst = $maxWidth/$maxHeight;
        if ($ratioSrc > $ratioDst) {
            $newW = $maxWidth; $newH = intval($maxWidth / $ratioSrc);
        } else {
            $newH = $maxHeight; $newW = intval($maxHeight * $ratioSrc);
        }

        $dst = imagecreatetruecolor($newW, $newH);
        if ($ext === 'png') {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
        }
        imagecopyresampled($dst, $img, 0,0,0,0, $newW, $newH, $w, $h);

        switch ($ext) {
            case 'jpg':
            case 'jpeg': imagejpeg($dst, $archivo, 75); break;
            case 'png':  imagepng($dst, $archivo);        break;
            case 'gif':  imagegif($dst, $archivo);        break;
            case 'bmp':  imagewbmp($dst, $archivo);       break;
        }

        return true;
    }

    public function actionBrowse($gal_id)
    {
        $gal = Galerias::findOne($gal_id);
        if (!$gal || $gal->gal_estado !== 'publicado') {
            return $this->renderPartial('browse', [
                'imagenes' => [],
                'mensaje'  => $gal ? 'Galería no publicada.' : 'Galería no existe.'
            ]);
        }
        $imgs = ImagenesGaleria::find()
            ->where(['img_gal_id' => $gal_id, 'img_estado' => 'publicado'])
            ->orderBy(['img_id' => SORT_DESC])
            ->all();

        return $this->renderPartial('browse', [
            'imagenes' => $imgs,
            'mensaje'  => null
        ]);
    }

    protected function findModel($img_id)
    {
        if (($m = ImagenesGaleria::findOne($img_id)) !== null) {
            return $m;
        }
        throw new NotFoundHttpException('La imagen no existe.');
    }


    /**
 * Sube imágenes desde TinyMCE y devuelve la URL pública completa
 *
 * @param int|null  $gal_id    ID de galería (opcional)
 * @param string    $ubicacion Carpeta dentro de /recursos/uploads (por defecto "tinyMCE")
 * @return array JSON con:
 *   - location: URL pública de la imagen
 *   - img_id:   ID de la imagen en la BD (si aplica)
 *   - error:    Mensaje de error (en caso de fallo)
 */
public function actionUpload($gal_id = null, $ubicacion = 'tinyMCE')
{
    Yii::$app->response->format = Response::FORMAT_JSON;

    try {
        // 1) Validación básica
        if (empty($_FILES['file'])) {
            throw new \Exception('No se recibió archivo');
        }

        // 2) Preparar nombre único
        $file   = $_FILES['file'];
        $name   = preg_replace('/[^a-zA-Z0-9\.\-_]/', '', $file['name']);
        $unique = uniqid('img_', true) . '_' . $name;
        $tmp    = $file['tmp_name'];

        // 3) Carpeta en disco: @webroot = panel-admin/web
        //    ../..               = SITIOS/rogeliomunoz_final
        $basePath = Yii::getAlias('@webroot/../../recursos/uploads/' . $ubicacion . '/');
        if (!is_dir($basePath) && !mkdir($basePath, 0755, true)) {
            throw new \Exception("No se pudo crear carpeta destino: {$basePath}");
        }

        // 4) Mover archivo
        $dest = $basePath . $unique;
        if (!move_uploaded_file($tmp, $dest)) {
            throw new \Exception("Error al subir el archivo a: {$dest}");
        }

        // 5) Redimensionar / orientar
        if (!$this->redimensionarYCorregir($dest)) {
            throw new \Exception("Error al procesar imagen en: {$dest}");
        }

        // 6) Guardar en BD (opcional)
        $relative = $ubicacion . '/' . $unique;
        $imgId    = null;
        if ($gal_id) {
            $model = new ImagenesGaleria();
            $model->img_gal_id      = $gal_id;
            $model->img_ruta        = $relative;
            $model->img_descripcion = '';
            $model->img_estado      = 'publicado';
            if (!$model->save()) {
                $errs = json_encode($model->errors);
                throw new \Exception("Error guardando en BD: {$errs}");
            }
            $imgId = $model->img_id;
        }

        // 7) Construir URL pública REAL
        //    baseUrl = /SITIOS/rogeliomunoz_final/panel-admin/web
        //    rootUrl = quitamos '/panel-admin/web' → '/SITIOS/rogeliomunoz_final'
        $hostInfo = Yii::$app->request->hostInfo;      // ej: http://localhost:8888
        $baseUrl  = Yii::$app->request->baseUrl;       // ej: /SITIOS/rogeliomunoz_final/panel-admin/web
        $rootUrl  = preg_replace('#/panel-admin(/web)?#', '', $baseUrl);
        // queda '/SITIOS/rogeliomunoz_final'
        $publicUrl = "{$hostInfo}{$rootUrl}/recursos/uploads/{$relative}";

        return [
            'location' => $publicUrl,
            'img_id'   => $imgId,
        ];

    } catch (\Exception $e) {
        Yii::error($e->getMessage(), __METHOD__);
        Yii::$app->response->statusCode = 500;
        return ['error' => $e->getMessage()];
    }
}



}
