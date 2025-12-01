<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
/* ============================
   ESTRUCTURA GENERAL
   ============================ */
.site-login {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
    background: url('<?= Yii::getAlias('@web/img/fondo_login.jpg'); ?>') no-repeat center center fixed;
    background-size: cover;
    font-family: "Inter", "Nunito Sans", system-ui, sans-serif;
    position: relative;
}
.site-login::before {
    content: "";
    position: absolute;
    inset: 0;
    backdrop-filter: blur(8px);
    background: rgba(255, 255, 255, 0.05);
}

/* ============================
   TARJETA LOGIN
   ============================ */
.login-card {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 420px;
    padding: 45px 40px;
    border-radius: 18px;
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    text-align: center;
    animation: fadeInUp 0.6s ease-out both;
}

/* ============================
   CABECERA (IMAGENES)
   ============================ */
.login-header {
    margin-bottom: 35px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

/* Imagen de usuario (avatar) */
.login-header img:first-child {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    object-fit: cover;
    background: linear-gradient(145deg, #f9fafb, #e5e7eb);
    border: 3px solid rgba(255,255,255,0.8);
    box-shadow:
        0 3px 10px rgba(0,0,0,0.15),
        inset 0 1px 4px rgba(255,255,255,0.5);
    margin-bottom: 15px;
    transition: all 0.3s ease;
}
.login-header img:first-child:hover {
    transform: scale(1.05);
    box-shadow:
        0 6px 18px rgba(0,0,0,0.25),
        inset 0 1px 5px rgba(255,255,255,0.6);
}

/* Logo texto */
.login-header img:last-child {
    max-width: 200px;
    margin-top: 5px;
}

/* ============================
   FORMULARIO
   ============================ */
.input-group {
    position: relative;
    width: 100%;
    margin-bottom: 20px;
}

/* Inputs */
.input-group .form-control {
    width: 90%;
    height: 40px;
    background: rgba(255, 255, 255, 0.7);
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    padding: 10px 16px;
    font-size: 15px;
    color: #222;
    transition: all 0.25s ease;
    box-shadow: inset 0 1px 2px rgba(255, 255, 255, 0.4),
                0 2px 5px rgba(0,0,0,0.05);
}
.input-group .form-control:focus {
    border-color: #3B82F6;
    background: rgba(255, 255, 255, 0.85);
    box-shadow: 0 0 10px rgba(59,130,246,0.3);
}

/* Toggle del password */
.input-group-text {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    background: transparent;
    cursor: pointer;
    color: #444;
    font-size: 15px;
}

/* ============================
   CHECKBOX
   ============================ */
.form-check {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    margin-top: 10px;
    margin-bottom: 25px;
}
.form-check-input {
    border-color: #0066ff;
    cursor: pointer;
    transform: scale(1.2);
    margin-right: 6px;
}
.form-check-label {
    margin-left: 5px;
    color: #222;
    font-size: 14px;
}

/* ============================
   BOTÓN
   ============================ */
.btn-login {
    display: inline-block;
    width: 100%;
    background: linear-gradient(180deg, #3B82F6, #2563EB);
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 13px 0;
    font-size: 15px;
    font-weight: 500;
    letter-spacing: 0.3px;
    transition: all 0.25s ease;
    box-shadow: 0 4px 14px rgba(59,130,246,0.35);
}
.btn-login:hover {
    background: linear-gradient(180deg, #2563EB, #1D4ED8);
    box-shadow: 0 6px 20px rgba(59,130,246,0.45);
    transform: translateY(-1px);
}

/* ============================
   FOOTER
   ============================ */
.login-footer {
    text-align: center;
    margin-top: 25px;
    font-size: 13px;
    color: #444;
}
.login-footer a {
    color: #007bff;
    text-decoration: none;
}
.login-footer a:hover {
    color: #004fa3;
    text-decoration: underline;
}

/* ============================
   ANIMACIÓN
   ============================ */
@keyframes fadeInUp {
    from {opacity: 0; transform: translateY(15px);}
    to {opacity: 1; transform: translateY(0);}
}
</style>


<div class="site-login">
    <div class="login-card">
        <div class="login-header">
            <?= Html::img('@web/img/profile.png') ?>
            <?= Html::img('@web/img/logo_negro.png') ?>
        </div>

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'fieldConfig' => ['template' => "{input}\n{error}"],
        ]); ?>

        <div class="input-group">
            <?= $form->field($model, 'usu_username')->textInput([
                'class' => 'form-control',
                'placeholder' => 'Correo o usuario',
            ]) ?>
        </div>

        <div class="input-group">
            <?= $form->field($model, 'usu_password')->passwordInput([
                'class' => 'form-control',
                'id' => 'password-input',
                'placeholder' => 'Contraseña',
            ]) ?>
            <span class="input-group-text" id="toggle-password">
                <i class="fa fa-eye"></i>
            </span>
        </div>

        <div class="form-check form-switch mb-3 text-start">
            <?= $form->field($model, 'rememberMe')->checkbox([
                'class' => 'form-check-input',
                'template' => '{input}<label class="form-check-label ms-1">Recuérdame</label>',
            ]) ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Iniciar Sesión', ['class' => 'btn-login']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <div class="login-footer">
            <?= Yii::$app->params['texto_login']; ?><br>
            <a href="<?= Yii::$app->params["sitio_autor"]; ?>" target="_blank">
                <?= Yii::$app->params["meta_author"]; ?> <?= " - " . date("Y"); ?>
            </a><br>
            Todos los derechos reservados
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const toggle = document.getElementById("toggle-password");
    const passwordInput = document.getElementById("password-input");
    toggle.addEventListener("click", function() {
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggle.innerHTML = '<i class="fa fa-eye-slash"></i>';
        } else {
            passwordInput.type = "password";
            toggle.innerHTML = '<i class="fa fa-eye"></i>';
        }
    });
});
</script>