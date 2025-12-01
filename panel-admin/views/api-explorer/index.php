<?php
/**
 * @var yii\web\View $this
 * @var array         $apiControllers
 */
use yii\helpers\Html;
use yii\helpers\Url;

// Título de la página
$this->title = 'Catálogo de APIs CMS V5';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="api-catalogo" style="padding: 20px;">

    <!-- TÍTULO -->
    <h1 style="margin-bottom: 20px;"><?= Html::encode($this->title) ?></h1>

    <!-- BUSCADOR -->
    <input
      type="text"
      id="buscador-api"
      placeholder="Buscar controlador..."
      style="
        width: 100%;
        padding: 8px;
        font-size: 14px;
        margin-bottom: 20px;
        box-sizing: border-box;
      "
    />

    <?php if (!empty($apiControllers)): ?>
        <div id="controladores-api">

            <?php foreach ($apiControllers as $controller): ?>
                <div class="controlador"
                     style="
                       margin-bottom: 20px;
                       border: 1px solid #ccc;
                       border-radius: 4px;
                       overflow: hidden;
                     "
                >
                    <!-- CABECERA COLAPSABLE -->
                    <div
                      class="header-controlador"
                      style="
                        padding: 12px 16px;
                        background: #f5f5f5;
                        font-weight: bold;
                        cursor: pointer;
                        user-select: none;
                      "
                    >
                        <?= Html::encode($controller['name']) ?>
                    </div>

                    <!-- CUERPO (oculto inicialmente) -->
                    <div
                      class="body-controlador"
                      style="
                        display: none;
                        padding: 12px 16px;
                        background: #fff;
                      "
                    >
                        <table
                          border="1"
                          cellpadding="8"
                          cellspacing="0"
                          style="
                            width: 100%;
                            border-collapse: collapse;
                            font-size: 14px;
                          "
                        >
                            <thead>
                                <tr style="background: #eee;">
                                    <th style="text-align: left;">Método</th>
                                    <th style="text-align: left;">URL</th>
                                    <th style="text-align: left;">Descripción</th>
                                    <th style="text-align: left;">Probar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($controller['acciones'] as $accion): ?>
                                    <tr>
                                        <!-- 1) Método HTTP -->
                                        <td style="width: 70px;">
                                            <?= Html::encode($accion['method']) ?>
                                        </td>

                                        <!-- 2) URL absoluta -->
                                        <?php
                                            $hostInfo     = Yii::$app->request->hostInfo;   // e.g. "http://localhost:8888"
                                            $baseUrl      = Yii::$app->request->baseUrl;    // e.g. "/CMS_V4/panel-admin/web"
                                            // $accion['url'] es "/api/xxx" tal como lo definimos en el controller
                                            $rutaAbsoluta = $hostInfo . $baseUrl . $accion['url'];
                                        ?>
                                        <td style="font-family: monospace;">
                                            <?= Html::encode($rutaAbsoluta) ?>
                                        </td>

                                        <!-- 3) Descripción -->
                                        <td>
                                            <?= Html::encode($accion['description']) ?>
                                        </td>

                                        <!-- 4) “Probar” / Formulario POST -->
                                        <td style="width: 240px;">

                                            <!-- ====== CASO: GET ====== -->
                                            <?php if ($accion['method'] === 'GET'): ?>
                                                <?= Html::a(
                                                    'Probar',
                                                    $rutaAbsoluta,
                                                    [
                                                      'target' => '_blank',   // abre en nueva pestaña
                                                    ]
                                                ) ?>

                                            <!-- ====== CASO: POST /api/contacto ====== -->
                                            <?php elseif (
                                                $accion['method'] === 'POST' &&
                                                $accion['url'] === '/api/contacto'
                                            ): ?>
                                                <button onclick="toggleContactoForm(this)" style="margin-bottom: 8px;">
                                                    Formulario POST
                                                </button>

                                                <div class="contacto-form-container" style="display: none; margin-top: 8px;">
                                                    <?php
                                                        //  A) Construir URL absoluta para GET /api/contacto/asuntos
                                                        $apiKey    = Yii::$app->params['api_secret_token'] ?? '';
                                                        $hostInfo2 = Yii::$app->request->hostInfo;
                                                        $baseUrl2  = Yii::$app->request->baseUrl;
                                                        $rutaAsuntosRel = '/api/contacto/asuntos';
                                                        $urlAsuntos = $hostInfo2 . $baseUrl2 . $rutaAsuntosRel;
                                                        if (!empty($apiKey)) {
                                                            $urlAsuntos .= (strpos($urlAsuntos, '?') === false ? '?' : '&')
                                                                          . 'api_key=' . urlencode($apiKey);
                                                        }

                                                        //  B) Intentar cargar el JSON de “asuntos”
                                                        $listaAsuntos = [];
                                                        try {
                                                            $jsonAs = @file_get_contents($urlAsuntos);
                                                            if ($jsonAs !== false) {
                                                                $dataAs = json_decode($jsonAs, true);
                                                                if (json_last_error() === JSON_ERROR_NONE && is_array($dataAs)) {
                                                                    $listaAsuntos = $dataAs;
                                                                }
                                                            }
                                                        } catch (\Exception $e) {
                                                            $listaAsuntos = [];
                                                        }
                                                    ?>

                                                    <!-- FORMULARIO PARA PROBAR POST /api/contacto -->
                                                    <form
                                                    id="form-enviar-contacto"
                                                    method="POST"
                                                    action="<?= Html::encode($rutaAbsoluta) ?>"
                                                    style="max-width: 450px;"
                                                    >
                                                        <div style="margin-bottom: 8px;">
                                                            <label for="c_nombre">Nombre:</label><br>
                                                            <input
                                                              id="c_nombre"
                                                              name="cor_nombre"
                                                              type="text"
                                                              required
                                                              style="width: 100%; padding: 6px;"
                                                            >
                                                        </div>
                                                        <div style="margin-bottom: 8px;">
                                                            <label for="c_correo">Email:</label><br>
                                                            <input
                                                              id="c_correo"
                                                              name="cor_correo"
                                                              type="email"
                                                              required
                                                              style="width: 100%; padding: 6px;"
                                                            >
                                                        </div>

                                                        <div style="margin-bottom: 8px;">
                                                            <label for="c_asunto">Asunto:</label><br>
                                                            <?php if (!empty($listaAsuntos) && is_array($listaAsuntos)): ?>
                                                                <select
                                                                  id="c_asunto"
                                                                  name="cor_asunto"
                                                                  required
                                                                  style="width: 100%; padding: 6px;"
                                                                >
                                                                    <option value="" disabled selected>Selecciona un asunto</option>
                                                                    <?php foreach ($listaAsuntos as $unAsunto): ?>
                                                                        <?php
                                                                            $id    = Html::encode($unAsunto['asu_id']   ?? '');
                                                                            $texto = Html::encode($unAsunto['asu_nombre'] ?? '');
                                                                        ?>
                                                                        <option value="<?= $id ?>">
                                                                            <?= $texto ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            <?php else: ?>
                                                                <!-- Si falla cargar “asuntos”, fallback a input numérico -->
                                                                <input
                                                                  id="c_asunto"
                                                                  name="cor_asunto"
                                                                  type="number"
                                                                  required
                                                                  min="1"
                                                                  placeholder="ID de asunto"
                                                                  style="width: 100%; padding: 6px;"
                                                                >
                                                            <?php endif; ?>
                                                        </div>

                                                        <div style="margin-bottom: 8px;">
                                                            <label for="c_mensaje">Mensaje:</label><br>
                                                            <textarea
                                                              id="c_mensaje"
                                                              name="cor_mensaje"
                                                              rows="4"
                                                              required
                                                              style="width: 100%; padding: 6px;"
                                                            ></textarea>
                                                        </div>

                                                        <!-- Si la API requiere api_key para POST -->
                                                        <?php if (!empty($apiKey)): ?>
                                                            <?= Html::hiddenInput('api_key', $apiKey) ?>
                                                        <?php endif; ?>

                                                        <button
                                                          type="submit"
                                                          style="
                                                            background: #007bff;
                                                            color: #fff;
                                                            border: none;
                                                            padding: 8px 12px;
                                                            border-radius: 4px;
                                                            cursor: pointer;
                                                          "
                                                        >
                                                          Enviar
                                                        </button>
                                                    </form>
                                                </div>
                                            <?php else: ?>
                                                <!-- Otros métodos no contemplados -->
                                                -
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    <?php else: ?>
        <p>No se encontraron controladores de API disponibles.</p>
    <?php endif; ?>

</div>

<!-- JS PARA:
     1) Filtrar controladores.
     2) Colapsar/expandir secciones.
     3) Mostrar/ocultar formulario de contacto.
-->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1) Filtrar controladores en tiempo real
    var inputBuscador = document.getElementById('buscador-api');
    inputBuscador.addEventListener('input', function () {
        var filtro = this.value.toLowerCase();
        document.querySelectorAll('.controlador').forEach(function (ctrl) {
            var nombre = ctrl.querySelector('.header-controlador').innerText.toLowerCase();
            ctrl.style.display = (nombre.indexOf(filtro) !== -1) ? '' : 'none';
        });
    });

    // 2) Toggle colapsable para cada “header-controlador”
    document.querySelectorAll('.header-controlador').forEach(function (header) {
        header.addEventListener('click', function () {
            var body = this.nextElementSibling;
            body.style.display = (body.style.display === 'none') ? 'block' : 'none';
        });
    });
});

// 3) Mostrar/ocultar el formulario de contacto
function toggleContactoForm(btn) {
    var container = btn.closest('td').querySelector('.contacto-form-container');
    if (!container) return;
    container.style.display = (container.style.display === 'none') ? 'block' : 'none';
}
</script>