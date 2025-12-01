<?php

namespace app\controllers;

use app\models\Curriculum;
use app\services\CurriculumExporter;
use app\services\CurriculumWordExporter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\ListItemRun;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Style\Font;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Yii;
use DOMDocument;
use DOMNode;
use DOMNodeList;
use DOMElement;
use DOMText;
use app\helpers\LibreriaHelper;

class CurriculumController extends Controller
{
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

    public function actionAdministrar()
    {
        $model = $this->findModel(1);
        $model->cur_per_id = 1;

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Curriculum actualizado correctamente.');
            return $this->redirect(['administrar']);
        }

        return $this->render('administrar', [
            'model' => $model,
        ]);
    }

    protected function findModel($cur_id)
    {
        if (($model = Curriculum::findOne(['cur_id' => $cur_id])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionVisualizar()
    {
        $curriculumId = 1;
        try {
            $datos = $this->obtenerDatosCv($curriculumId);
        } catch (\Exception $e) {
            Yii::error("Error al obtener datos del curriculum: " . $e->getMessage());
            Yii::$app->session->setFlash('error', 'No se pudieron cargar los datos del curriculum.');
            return $this->redirect(['index']);
        }
        return $this->render('visualizar', $datos);
    }

    public function actionDescargarWord()
    {
        try {
            $datos = $this->obtenerDatosCv(1);
            $filePath = CurriculumWordExporter::export($datos);
            return Yii::$app->response->sendFile($filePath)->on(
                \yii\web\Response::EVENT_AFTER_SEND,
                fn($event) => @unlink($event->data),
                $filePath
            );
        } catch (\Throwable $e) {
            Yii::error($e->getMessage(), __METHOD__);
            Yii::$app->session->setFlash('error', 'Error al generar Word.');
            return $this->redirect(['visualizar']);
        }
    }

    public function actionDescargarPdf()
    {
        try {
            $datos = $this->obtenerDatosCv(1);
            $filePath = CurriculumExporter::exportToPdf($datos);
            return Yii::$app->response->sendFile($filePath)->on(
                \yii\web\Response::EVENT_AFTER_SEND,
                fn($event) => @unlink($event->data),
                $filePath
            );
        } catch (\Throwable $e) {
            Yii::error($e->getMessage(), __METHOD__);
            Yii::$app->session->setFlash('error', 'Error al generar PDF.');
            return $this->redirect(['visualizar']);
        }
    }

    protected function obtenerDatosCv($curriculumId) {
        $datos = [
            "curriculum"     => Curriculum::obtenerDatosCurriculum($curriculumId),
            "perfil"         => Curriculum::obtenerDatosPerfil($curriculumId),
            "experiencias"   => Curriculum::obtenerExperiencias($curriculumId),
            "formaciones"    => Curriculum::obtenerFormaciones($curriculumId),
            "cursos"         => Curriculum::obtenerCursos($curriculumId),
            "certificaciones"=> Curriculum::obtenerCertificaciones($curriculumId),
            "habilidades"    => Curriculum::obtenerHabilidades($curriculumId),
            "herramientas"   => Curriculum::obtenerHerramientas($curriculumId)
        ];
        if (empty($datos['curriculum']) || empty($datos['perfil'])) {
            throw new \yii\web\NotFoundHttpException("Faltan datos esenciales del curriculum o perfil.");
        }
        return $datos;
    }

    protected function formatDateHelper($dateString, $isEndDate = false) {
        if (empty($dateString)) {
            return $isEndDate ? "Actualidad" : "N/A";
        }
        $timestamp = strtotime($dateString);
        if ($timestamp === false) {
            return $isEndDate ? "Actualidad" : "N/A";
        }
        $year = date('Y', $timestamp);
        $monthName = LibreriaHelper::obtenerNombreMes(date('F', $timestamp));
        return $monthName . ' ' . $year;
    }

    protected function sanitizeFilename($filename) {
        if (empty($filename)) {
            return 'documento_cv';
        }
        $filename = str_replace([
            'ñ', 'Ñ', 'á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ü', 'Ü'
        ], [
            'n', 'N', 'a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'u', 'U'
        ], $filename);
        if (function_exists('iconv')) {
            $filename = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $filename);
        }
        $filename = strtolower($filename);
        $filename = preg_replace('/[^a-z0-9_-]+/', '_', $filename);
        $filename = preg_replace('/_+/', '_', $filename);
        $filename = trim($filename, '_');
        if (empty($filename)) {
            return 'documento_cv';
        }
        return $filename;
    }

    public function actionDescargarLatex()
    {
        try {
            $datos = $this->obtenerDatosCv(1);
            $filePath = CurriculumExporter::exportToLatex($datos);
            return Yii::$app->response->sendFile($filePath)->on(
                \yii\web\Response::EVENT_AFTER_SEND,
                fn($event) => @unlink($event->data),
                $filePath
            );
        } catch (\Throwable $e) {
            Yii::error($e->getMessage(), __METHOD__);
            Yii::$app->session->setFlash('error', 'Error al generar LaTeX.');
            return $this->redirect(['visualizar']);
        }
    }
}
