<?php

namespace app\services;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\ListItem as ListStyle;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\ListItemRun;
use Yii;
use app\helpers\LibreriaHelper;

class CurriculumWordExporter
{
    public static function export(array $datos): string
    {
        $perfil = $datos['perfil'];
        $curriculum = $datos['curriculum'];
        $experiencias = $datos['experiencias'];
        $formaciones = $datos['formaciones'];
        $certificaciones = $datos['certificaciones'];
        $cursos = $datos['cursos'];
        $habilidades = $datos['habilidades'];
        $herramientas = $datos['herramientas'];

        $phpWord = new PhpWord();
        $section = $phpWord->addSection([
            'marginLeft' => 1134, 'marginRight' => 1134,
            'marginTop' => 1134, 'marginBottom' => 1134
        ]);

        $phpWord->addFontStyle('fontNombre', ['name' => 'Arial', 'size' => 16, 'bold' => true]);
        $phpWord->addFontStyle('fontSubHeader', ['name' => 'Arial', 'size' => 10, 'italic' => true]);
        $phpWord->addParagraphStyle('pStyleNombre', ['alignment' => Jc::CENTER]);
        $phpWord->addParagraphStyle('pStyleSubHeader', ['alignment' => Jc::CENTER, 'spaceAfter' => 40]);
        $phpWord->addParagraphStyle('pStyleTituloSeccion', ['size' => 11, 'bold' => true, 'spaceBefore' => 240, 'spaceAfter' => 80]);
        $phpWord->addParagraphStyle('pStyleSubtituloItem', ['size' => 9.5, 'bold' => true, 'spaceBefore' => 120]);
        $phpWord->addParagraphStyle('pStyleFechaPeriodo', ['size' => 8.5, 'italic' => true]);

        // Header
        $section->addText(htmlspecialchars($perfil['per_nombre'] ?? ''), 'fontNombre', 'pStyleNombre');
        $section->addText(htmlspecialchars($curriculum['cur_titulo'] ?? ''), 'fontSubHeader', 'pStyleSubHeader');

        // Experiencia
        $section->addText("Experiencia Profesional", null, 'pStyleTituloSeccion');
        foreach ($experiencias as $exp) {
            $section->addText(htmlspecialchars($exp['exp_cargo'] ?? '') . " - " . htmlspecialchars($exp['exp_empresa'] ?? ''), null, 'pStyleSubtituloItem');
            $ini = self::formatDate($exp['exp_fecha_inicio'] ?? null);
            $fin = self::formatDate($exp['exp_fecha_fin'] ?? null, true);
            $section->addText("$ini - $fin", null, 'pStyleFechaPeriodo');
        }

        // Educación
        $section->addText("Educación", null, 'pStyleTituloSeccion');
        foreach ($formaciones as $for) {
            $section->addText(htmlspecialchars($for['for_grado_titulo'] ?? '') . " - " . htmlspecialchars($for['for_institucion'] ?? ''), null, 'pStyleSubtituloItem');
            $ini = self::formatDate($for['for_fecha_inicio'] ?? null);
            $fin = self::formatDate($for['for_fecha_fin'] ?? null, true);
            $section->addText("$ini - $fin", null, 'pStyleFechaPeriodo');
        }

        $fileName = "Curriculum_" . self::sanitizeFilename($perfil['per_nombre'] ?? 'Usuario') . ".docx";
        $path = Yii::getAlias('@runtime/' . $fileName);
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($path);

        return $path;
    }

    protected static function formatDate($dateString, $isEndDate = false): string
    {
        if (empty($dateString)) return $isEndDate ? "Actualidad" : "N/A";
        $timestamp = strtotime($dateString);
        if ($timestamp === false) return $isEndDate ? "Actualidad" : "N/A";
        $year = date('Y', $timestamp);
        $month = LibreriaHelper::obtenerNombreMes(date('F', $timestamp));
        return $month . ' ' . $year;
    }

    protected static function sanitizeFilename($filename): string
    {
        $filename = preg_replace('/[^\w\-]/', '_', $filename);
        return strtolower(trim($filename, '_')) ?: 'documento_cv';
    }
}
