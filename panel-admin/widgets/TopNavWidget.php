<?php
namespace app\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

class TopNavWidget extends Widget
{
    /**
     * URL o path de la imagen de usuario.
     * @var string
     */
    public $imagen;

    /**
     * Genera el HTML del navbar y registra JS para el reloj.
     * @return string
     */
    public function run()
    {
        // Hora inicial del servidor (formato HH:MM:SS)
        $initial = date('H:i:s');
        list($h, $m, $s) = explode(':', $initial);

        // JS que se ejecutará al cargar y actualizará los spans cada segundo
        $js = <<<JS
(function() {
    const root = document.getElementById('live-clock');
    if (!root) return;
    const hrs  = root.querySelector('.hours');
    const mins = root.querySelector('.minutes');
    const secs = root.querySelector('.seconds');

    // Mostrar hora inicial del servidor
    hrs.textContent  = '$h';
    mins.textContent = '$m';
    secs.textContent = '$s';

    function updateClock() {
        const now = new Date();
        hrs.textContent  = String(now.getHours()).padStart(2, '0');
        mins.textContent = String(now.getMinutes()).padStart(2, '0');
        secs.textContent = String(now.getSeconds()).padStart(2, '0');
    }

    // Iniciar actualización inmediata y programada
    updateClock();
    setInterval(updateClock, 1000);
})();
JS;
        Yii::$app->view->registerJs($js, View::POS_READY);

        // Construir el navbar
        return Html::tag('div',
            Html::tag('div',
                Html::tag('nav',
                    $this->renderNavContent(),
                    ['class' => 'navbar navbar-expand']
                ),
                ['class' => 'nav_menu']
            ),
            ['class' => 'top_nav']
        );
    }

    /**
     * Renderiza el contenido interno del navbar: toggle, reloj, búsqueda y menú de usuario.
     * @return string
     */
    protected function renderNavContent()
    {
        // Botón toggle para el sidebar
        $toggleBtn = Html::button('<i class="bi bi-list"></i>', [
            'class'       => 'btn btn-toggle me-3',
            'onclick'     => "document.body.classList.toggle('sidebar-collapsed')",
            'aria-label'  => 'Toggle sidebar',
        ]);

        // Contenedor del reloj con spans separados para horas, minutos y segundos
        $clock = Html::tag('div',
            Html::tag('span', '', ['class' => 'hours']) . ':' .
            Html::tag('span', '', ['class' => 'minutes']) . ':' .
            Html::tag('span', '', ['class' => 'seconds']),
            ['id' => 'live-clock', 'class' => 'cms-clock top-nav-clock-container']
        );

        // Contenedor derecho: formulario de búsqueda + menú usuario
        $right = Html::tag('div',
            $this->renderSearchForm() . $this->renderUserMenu(),
            ['class' => 'd-flex align-items-center ms-auto']
        );

        return $toggleBtn . $clock . $right;
    }

    /**
     * Renderiza el formulario de búsqueda global.
     * @return string
     */
    protected function renderSearchForm()
    {
        return Html::beginForm(Url::to(['site/search-global']), 'get', [
                'class' => 'cms-search-form d-flex me-3 align-items-center'
            ])
            . Html::input('search', 'q', '', [
                'class' => 'form-control cms-search-input',
                'placeholder' => 'Buscar...',
                'aria-label' => 'Buscar'
            ])
            . Html::button('<i class="fa fa-search"></i>', [
                'type' => 'submit',
                'class' => 'cms-search-btn'
            ])
            . Html::endForm();
    }

    /**
     * Renderiza el menú de usuario con dropdown.
     * @return string
     */
    protected function renderUserMenu()
    {
        $username = Yii::$app->user->identity->usu_username;
        $id       = Yii::$app->user->identity->id;

        $img = Html::img($this->imagen, [
            'alt'   => 'user-img',
            'style' => 'width:25px;height:25px;object-fit:cover;border-radius:50%'
        ]);

        $itemsConfig = [
            ['label' => 'Mi Perfil',   'url' => ['/user/myprofile',  'usu_id' => $id]],
            ['label' => 'Mi Password', 'url' => ['/user/mypassword', 'usu_id' => $id]],
            ['label' => 'Mi Correo',   'url' => ['/user/mymail',     'usu_id' => $id]],
            '-',
            ['label' => "Logout ($username)", 'url' => ['/site/logout']],
        ];

        $items = '';
        foreach ($itemsConfig as $item) {
            if ($item === '-') {
                $items .= Html::tag('li', Html::tag('hr', '', ['class' => 'dropdown-divider']));
            } else {
                $items .= Html::tag(
                    'li',
                    Html::a($item['label'], Url::to($item['url']), ['class' => 'dropdown-item'])
                );
            }
        }

        // IMPORTANTE: usar data-toggle (Bootstrap 3/4), no data-bs-toggle
        $toggle = Html::a(
            $img . " " . Html::encode($username) . " <span class='fa fa-angle-down'></span>",
            'javascript:void(0);',
            [
                'class'         => 'nav-link dropdown-toggle',
                'id'            => 'userDropdown',
                'role'          => 'button',
                'data-toggle'   => 'dropdown',   // <- clave para BS3/4
                'aria-haspopup' => 'true',
                'aria-expanded' => 'false',
            ]
        );

        // Para Bootstrap 3/4: dropdown-menu + dropdown-menu-right
        $dropdown = Html::tag('ul', $items, [
            'class'           => 'dropdown-menu dropdown-menu-right',
            'aria-labelledby' => 'userDropdown',
        ]);

        return Html::tag(
            'ul',
            Html::tag('li', $toggle . $dropdown, ['class' => 'nav-item dropdown']),
            ['class' => 'navbar-nav']
        );
    }
}
