<?php
session_start();
include __DIR__ . '/assets/config.php'; // carga $totalSteps, etc.
// En portada siempre 0% (aun no hemos empezado)
$progressPercentage = 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Bienvenido al Instalador CMS V5</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    .welcome-card {
      background: #fff;
      border-radius: 8px;
      padding: 2rem;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      max-width: 700px;
      margin: 2rem auto;
      text-align: center;
    }
    .welcome-card h1 {
      margin-bottom: 1rem;
    }
    .welcome-card .description {
      margin: 1.5rem 0;
      font-size: 1rem;
      line-height: 1.5;
      color: #333;
    }
    .welcome-card .btn-start {
      font-size: 1.1rem;
      padding: 0.75rem 2rem;
      border: none;
      border-radius: 5px;
      background-color: #007bff;
      color: #fff;
      cursor: pointer;
      transition: background-color .2s ease;
    }
    .welcome-card .btn-start:hover {
      background-color: #0069d9;
    }
    .progress-container {
      margin: 1.5rem 0;
    }
    .progress-bar {
      background: #e9ecef;
      border-radius: 5px;
      overflow: hidden;
      height: 12px;
    }
    .progress {
      background: #007bff;
      width: <?= $progressPercentage ?>%;
      height: 100%;
      transition: width .3s ease;
    }
    .footer {
      text-align: center;
      margin: 2rem 0 1rem;
      color: #666;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>
  <div class="welcome-card">
    <h1>🚀 Bienvenido al Instalador del CMS V5</h1>
    <hr>
    <div class="progress-container">
      <div class="progress-bar">
        <div class="progress"></div>
      </div>
      <div style="margin-top:8px; font-size:0.85rem; color:#555;"><?= round($progressPercentage) ?>%</div>
    </div>
    <div class="description">
      Este asistente te guiará a través de <?= $totalSteps ?> pasos para completar la configuración de tu CMS.
    </div>
    <button class="btn-start" onclick="window.location.href='step1.php'">
      Comenzar Instalación 🚀
    </button>
  </div>

  <div class="footer">
    Cápsula Tech © <?= date('Y') ?>
  </div>
</body>
</html>
