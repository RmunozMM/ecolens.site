<?php

namespace app\services;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\ListItemRun;
use PhpOffice\PhpWord\Style\ListItem as ListStyle;
use PhpOffice\PhpWord\SimpleType\Jc;
use Mpdf\Mpdf;
use Yii;
use app\helpers\LibreriaHelper;

class CurriculumExporter
{
    public static function sanitizeFilename($filename): string
    {
        if (empty($filename)) return 'documento_cv';

        $filename = str_replace(
            ['ñ', 'Ñ', 'á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ü', 'Ü'],
            ['n', 'N', 'a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'u', 'U'],
            $filename
        );
        if (function_exists('iconv')) {
            $filename = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $filename);
        }
        $filename = strtolower($filename);
        $filename = preg_replace('/[^a-z0-9_-]+/', '_', $filename);
        $filename = preg_replace('/_+/', '_', $filename);
        return trim($filename, '_') ?: 'documento_cv';
    }

    public static function formatDate($dateString, $isEndDate = false): string
    {
        if (empty($dateString)) return $isEndDate ? "Actualidad" : "N/A";
        $timestamp = strtotime($dateString);
        if ($timestamp === false) return $isEndDate ? "Actualidad" : "N/A";
        $year = date('Y', $timestamp);
        $month = LibreriaHelper::obtenerNombreMes(date('F', $timestamp));
        return $month . ' ' . $year;
    }

    public static function exportToPdf(array $datos): string
    {
        $html = Yii::$app->controller->renderPartial('visualizar', $datos);
        $css = file_exists($cssPath = Yii::getAlias('@webroot/css/cv-pdf.css')) ? file_get_contents($cssPath) : '';
        $mpdf = new Mpdf([
            'format' => 'A4',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
            'default_font_size' => 8.5,
            'default_font' => 'dejavusans',
        ]);
        $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

        $fileName = "Curriculum_" . self::sanitizeFilename($datos['perfil']['per_nombre'] ?? 'Usuario') . ".pdf";
        $path = Yii::getAlias('@runtime/' . $fileName);
        $mpdf->Output($path, \Mpdf\Output\Destination::FILE);
        return $path;
    }

    public static function exportToLatex(array $datos): string
    {
        $perfil = $datos['perfil'];
        $experiencias = $datos['experiencias'];
        $formaciones = $datos['formaciones'];

        $nombre = $perfil['per_nombre'] ?? 'Usuario';
        $telefono = $perfil['per_telefono'] ?? '';
        $correo = $perfil['per_correo'] ?? '';
        $resumen = strip_tags($datos['curriculum']['cur_resumen_profesional'] ?? '');

        $latex = <<<EOT
\\documentclass[11pt]{article}
\\usepackage[utf8]{inputenc}
\\usepackage[margin=1in]{geometry}
\\title{Curriculum de {$nombre}}
\\author{}
\\begin{document}
\\maketitle

\\section*{Resumen Profesional}
{$resumen}

\\section*{Experiencia Profesional}
\\begin{itemize}
EOT;

        foreach ($experiencias as $exp) {
            $ini = self::formatDate($exp['exp_fecha_inicio'] ?? null);
            $fin = self::formatDate($exp['exp_fecha_fin'] ?? null, true);
            $cargo = $exp['exp_cargo'] ?? '';
            $empresa = $exp['exp_empresa'] ?? '';
            $desc = strip_tags($exp['exp_descripcion'] ?? '');
            $latex .= "\n\\item \\textbf{{$cargo}} en $empresa ($ini - $fin)\\\\ $desc";
        }

        $latex .= "\n\\end{itemize}\n\\section*{Educación}\n\\begin{itemize}\n";

        foreach ($formaciones as $for) {
            $ini = self::formatDate($for['for_fecha_inicio'] ?? null);
            $fin = self::formatDate($for['for_fecha_fin'] ?? null, true);
            $grado = $for['for_grado_titulo'] ?? '';
            $inst = $for['for_institucion'] ?? '';
            $latex .= "\n\\item \\textbf{{$grado}} en $inst ($ini - $fin)";
        }

        $latex .= "\n\\end{itemize}\n\\end{document}\n";

        $fileName = 'Curriculum_' . self::sanitizeFilename($nombre) . '.tex';
        $path = Yii::getAlias('@runtime/' . $fileName);
        file_put_contents($path, $latex);
        return $path;
    }
}