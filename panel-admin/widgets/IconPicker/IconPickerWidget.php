<?php

namespace app\widgets\iconpicker;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

class IconPickerWidget extends Widget
{
    public $model;
    public $attribute;
    private $iconListPath;

    public function init()
    {
        parent::init();
        $this->iconListPath = __DIR__ . "/bootstrap_icons.json"; // Ruta del JSON
    }

    public function run()
    {
        $inputName = Html::getInputName($this->model, $this->attribute);

        // Obtener ícono y color almacenados
        $iconData = explode('|', $this->model->{$this->attribute} ?? 'bi-house|#000000');
        $selectedIcon = $iconData[0];
        $selectedColor = $iconData[1] ?? '#000000';

        // Leer el JSON de iconos
        $icons = file_exists($this->iconListPath) ? Json::decode(file_get_contents($this->iconListPath)) : [];
        $iconCount = count($icons);
        $iconsJson = json_encode($icons, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return <<<HTML
        <div class="card">
            <div class="card-header text-white bg-primary">
                <strong>Seleccionar Ícono</strong>
            </div>
            <div class="card-body text-center">
                <input type="hidden" id="selected-icon" name="{$inputName}" value="{$selectedIcon}|{$selectedColor}">

                <!-- Dropdown para seleccionar ícono -->
                <button class="btn btn-secondary dropdown-toggle w-100 d-flex align-items-center justify-content-center" type="button" id="iconDropdown" data-bs-toggle="dropdown">
                    <i id="icon-preview" class="{$selectedIcon}" style="color: {$selectedColor};"></i> 
                    <span class="ms-2">Elegir Ícono</span> 
                    <span class="ms-2 badge bg-light text-dark">({$iconCount} íconos)</span>
                </button>

                <ul class="dropdown-menu w-100" id="icon-list">
                    <li class="p-2">
                        <input type="text" id="icon-search" class="form-control" placeholder="Buscar ícono..." autocomplete="off">
                    </li>
                    <div id="icon-container" class="p-2 icon-container"></div>
                </ul>

                <!-- Vista previa del ícono seleccionado -->
                <div class="mt-3">
                    <label>Vista Previa:</label>
                    <div id="icon-preview-container" class="preview-box border rounded p-2">
                        <i id="preview-icon" class="{$selectedIcon}" style="font-size: 3rem; color: {$selectedColor};"></i>
                    </div>
                </div>

                <!-- Selector de Color -->
                <div class="mt-3">
                    <label for="icon-color">Color del Ícono:</label>
                    <input type="color" id="icon-color" class="form-control" value="{$selectedColor}">
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var iconSearch = document.getElementById('icon-search');
                var iconContainer = document.getElementById('icon-container');
                var iconPreview = document.getElementById('icon-preview');
                var previewIcon = document.getElementById('preview-icon');
                var selectedIcon = document.getElementById('selected-icon');
                var colorPicker = document.getElementById('icon-color');
                var dropdownButton = document.getElementById('iconDropdown');

                // Asegurar que icons se define correctamente desde PHP
                var icons = JSON.parse('{$iconsJson}');

                function renderIcons(filter = '') {
                    iconContainer.innerHTML = ''; // Limpiar la lista antes de renderizar
                    icons.forEach(function(icon) {
                        if (icon.includes(filter.toLowerCase())) {
                            var div = document.createElement('div');
                            div.classList.add('p-2', 'icon-item', 'd-flex', 'align-items-center');
                            div.style.cursor = 'pointer';
                            div.setAttribute('data-icon', icon);
                            div.innerHTML = '<i class="' + icon + ' fa-2x me-2"></i> <span>' + icon.replace('bi-', '') + '</span>';
                            div.addEventListener('click', function() {
                                var selected = this.getAttribute('data-icon');
                                selectedIcon.value = selected + "|" + colorPicker.value;
                                previewIcon.className = selected;
                                previewIcon.style.color = colorPicker.value;
                                iconPreview.className = selected + " me-2"; 
                                iconPreview.style.color = colorPicker.value;
                                dropdownButton.innerHTML = '<i class="'+ selected +' me-2" style="color:' + colorPicker.value + ';"></i> <span>Elegir Ícono</span> <span class="ms-2 badge bg-light text-dark">(' + icons.length + ' íconos)</span>';
                            });
                            iconContainer.appendChild(div);
                        }
                    });
                }

                iconSearch.addEventListener('input', function() {
                    renderIcons(iconSearch.value);
                });

                colorPicker.addEventListener('input', function() {
                    previewIcon.style.color = colorPicker.value;
                    iconPreview.style.color = colorPicker.value;
                    selectedIcon.value = selectedIcon.value.split('|')[0] + "|" + colorPicker.value;
                });

                renderIcons();
            });
            </script>

        <style>
        .icon-container {
            max-height: 250px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }
        .icon-item {
            padding: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .preview-box {
            min-height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        </style>
        HTML;
    }
}