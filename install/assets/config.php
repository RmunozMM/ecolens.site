<?php
session_start();

// Lista ordenada de pasos
$wizardSteps = [
  'step1.php'  => 'Requisitos del Sistema',
  'step2.php'  => 'Configuración BD',
  'step3.php'  => 'Importación Estructura',
  'step4.php'  => 'Usuario Administrador',
  'step5.php'  => 'Opciones Programador',
  'step6.php'  => 'Opciones Sitio',
  'step7.php'  => 'Opciones Meta',
  'step8.php'  => 'Módulos',
  'step9.php'  => 'Finalización',
];

$totalSteps       = count($wizardSteps);
$currentFile      = basename($_SERVER['PHP_SELF']);
$stepKeys         = array_keys($wizardSteps);
$currentIndex     = array_search($currentFile, $stepKeys, true) ?: 0;
$currentStep      = $currentIndex + 1;
$progressPercentage = ($currentStep / $totalSteps) * 100;

// Ruta al SQL
$archivo_base_datos = '../recursos/CMS_V5_FINAL.sql';
