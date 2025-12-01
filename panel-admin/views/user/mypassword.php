<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = "Actualizar Password";
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- Estilos personalizados -->
<style>
    .password-strength-bar {
        transition: all 0.3s ease;
        height: 20px;
    }
    .requirement-list {
        list-style: none;
        padding: 0;
        margin: 10px 0;
    }
    .requirement-item {
        margin: 5px 0;
        font-size: 0.9em;
    }
    .requirement-item i {
        margin-right: 8px;
        width: 15px;
    }
    .valid-requirement {
        color: #28a745;
    }
    .invalid-requirement {
        color: #dc3545;
    }
    .security-info {
        margin-top: 15px;
        font-size: 0.9em;
    }
    /* Aseguramos que los botones de la botonera queden en línea */
    .botonera .btn {
        margin-bottom: 0;
    }
</style>

<div class="users-create container">
    <h2 class="mb-4"><?= Html::encode($this->title) ?></h2>

    <?php if (!empty($msg)): ?>
        <div class="alert alert-success" role="alert">
            <?= $msg; ?>
        </div>
    <?php endif; ?>

    <?php $form = ActiveForm::begin([
        'method' => 'post',
        'id' => 'form-mypassword',
        'enableClientValidation' => true,
    ]); ?>

    <div class="row">
        <!-- Columna izquierda: Inputs y validaciones -->
        <div class="col-md-6">
            <!-- Campo Nueva Contraseña con toggle -->
            <div class="form-group">
                <label for="users-password1" class="control-label">Nueva Contraseña</label>
                <div class="input-group">
                    <input 
                        type="password"
                        id="users-password1"
                        class="form-control"
                        name="usu_password1"
                        placeholder="Mínimo 10 caracteres"
                        autocomplete="new-password"
                    />
                    <div class="input-group-append">
                        <span class="input-group-text password-toggle" id="toggle-password1" style="cursor: pointer;">
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Lista de requisitos -->
            <div class="form-group">
                <ul class="requirement-list">
                    <li class="requirement-item" id="req-length">
                        <i class="fa fa-times-circle"></i>10+ caracteres
                    </li>
                    <li class="requirement-item" id="req-lower">
                        <i class="fa fa-times-circle"></i>1 minúscula
                    </li>
                    <li class="requirement-item" id="req-upper">
                        <i class="fa fa-times-circle"></i>1 mayúscula
                    </li>
                    <li class="requirement-item" id="req-number">
                        <i class="fa fa-times-circle"></i>1 número
                    </li>
                    <li class="requirement-item" id="req-special">
                        <i class="fa fa-times-circle"></i>1 carácter especial (!@#$%&*?_)
                    </li>
                    <li class="requirement-item" id="req-hibp">
                        <i class="fa fa-times-circle"></i>No comprometida
                    </li>
                </ul>
            </div>

            <!-- Barra de fuerza y seguridad -->
            <div class="form-group">
                <div id="password-strength" class="progress" style="height: 8px; display: none;">
                    <div class="progress-bar password-strength-bar" role="progressbar" style="width: 0%;"></div>
                </div>
                <div class="security-info" id="security-info"></div>
            </div>

            <!-- Campo Repetir Contraseña con toggle -->
            <div class="form-group">
                <label for="users-password2" class="control-label">Repetir Contraseña</label>
                <div class="input-group">
                    <input 
                        type="password"
                        id="users-password2"
                        class="form-control"
                        name="usu_password2"
                        placeholder="Repite la contraseña"
                    />
                    <div class="input-group-append">
                        <span class="input-group-text password-toggle" id="toggle-password2" style="cursor: pointer;">
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>
                </div>
                <div id="password-match-alert" class="text-danger small mt-2" style="display: none;">
                    ⚠ Las contraseñas no coinciden
                </div>
            </div>
        </div>

        <!-- Columna derecha: Botonera en una fila y configuración -->
        <div class="col-md-6">
            <div class="border p-3">
                <div class="form-group mb-4">
                    <label for="password-length">Longitud:</label>
                    <input 
                        type="number" 
                        id="password-length" 
                        class="form-control" 
                        min="10" 
                        max="24" 
                        value="14"
                    >
                </div>
                <!-- Botonera en una fila -->
                <div class="row botonera">
                    <div class="col-md-4">
                        <button type="button" id="btn-generate" class="btn btn-info btn-block">
                            <i class="fa fa-key mr-1"></i>Generar
                        </button>
                    </div>
                    <div class="col-md-4">
                        <button type="button" id="btn-use" class="btn btn-warning btn-block">
                            <i class="fa fa-check mr-1"></i>Usar
                        </button>
                    </div>
                    <div class="col-md-4">
                        <?= Html::submitButton("<i class='fa fa-lock mr-1'></i>Actualizar", [
                            "class" => "btn btn-success btn-block",
                            "id" => "submit-btn",
                            "disabled" => true
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Fin row -->

    <?php ActiveForm::end(); ?>
</div>

<!-- Incluir zxcvbn para análisis avanzado -->
<script src="https://cdn.jsdelivr.net/npm/zxcvbn@4.4.2/dist/zxcvbn.js"></script>
<script>
// Función para alternar el tipo de input (password/text)
const setupToggle = (toggleId, inputId) => {
    const toggle = document.getElementById(toggleId);
    const input = document.getElementById(inputId);
    toggle.addEventListener('click', () => {
        if (input.type === "password") {
            input.type = "text";
            toggle.innerHTML = '<i class="fa fa-eye-slash"></i>';
        } else {
            input.type = "password";
            toggle.innerHTML = '<i class="fa fa-eye"></i>';
        }
    });
};

document.addEventListener("DOMContentLoaded", () => {
    // Configurar toggles para ambos campos
    setupToggle('toggle-password1', 'users-password1');
    setupToggle('toggle-password2', 'users-password2');

    const elements = {
        pass1: document.getElementById('users-password1'),
        pass2: document.getElementById('users-password2'),
        strengthBar: document.querySelector('#password-strength .progress-bar'),
        strengthContainer: document.getElementById('password-strength'),
        securityInfo: document.getElementById('security-info'),
        lengthInput: document.getElementById('password-length'),
        submitBtn: document.getElementById('submit-btn')
    };

    const checkRequirements = (password) => ({
        length: password.length >= 10,
        lower: /[a-z]/.test(password),
        upper: /[A-Z]/.test(password),
        number: /\d/.test(password),
        special: /[!@#$%&*?_]/.test(password)
    });

    const updateRequirementsUI = (requirements) => {
        Object.keys(requirements).forEach(req => {
            const element = document.getElementById(`req-${req}`);
            if (element) {
                const icon = element.querySelector('i');
                icon.className = requirements[req] 
                    ? 'fa fa-check-circle valid-requirement' 
                    : 'fa fa-times-circle invalid-requirement';
            }
        });
    };

    const evaluateSecurity = async (password) => {
        if (!password) return null;
        
        const requirements = checkRequirements(password);
        const allRequirementsMet = Object.values(requirements).every(Boolean);
        const hibpCheck = await checkHIBP(password);
        const zx = zxcvbn(password);
        const strength = zx.score; // 0 a 4
        
        return {
            requirements,
            allRequirementsMet,
            hibpCheck,
            strength,
            crackTime: zx.crack_times_display.offline_fast_hashing_1e10_per_second,
            feedback: zx.feedback
        };
    };

    const updateUI = async () => {
        const password = elements.pass1.value.trim();
        if (!password) {
            elements.strengthContainer.style.display = 'none';
            elements.securityInfo.innerHTML = '';
            const matchAlert = document.getElementById('password-match-alert');
            if (matchAlert) { matchAlert.style.display = 'none'; }
            elements.submitBtn.disabled = true;
            return;
        }
        const result = await evaluateSecurity(password);
        if (!result) {
            elements.strengthContainer.style.display = 'none';
            elements.securityInfo.innerHTML = '';
            elements.submitBtn.disabled = true;
            return;
        }
        updateRequirementsUI(result.requirements);
        
        // Actualizar el estado de "No comprometida" (HIBP)
        const hibpElement = document.getElementById('req-hibp');
        if (hibpElement) {
            hibpElement.querySelector('i').className = result.hibpCheck 
                ? 'fa fa-times-circle invalid-requirement' 
                : 'fa fa-check-circle valid-requirement';
        }

        // Actualizar la barra de fuerza
        const width = (result.strength + 1) * 20; // 0->20% ... 4->100%
        elements.strengthBar.style.width = `${width}%`;
        elements.strengthContainer.style.display = 'block';
        const strengthColors = ['danger', 'warning', 'info', 'primary', 'success'];
        elements.strengthBar.className = `progress-bar password-strength-bar bg-${strengthColors[result.strength]}`;
        
        let securityText = `Nivel de seguridad: ${['Muy débil', 'Débil', 'Moderado', 'Fuerte', 'Muy fuerte'][result.strength]}`;
        securityText += `<br>Tiempo estimado de crackeo: ${result.crackTime || 'N/A'}`;
        if (result.feedback && result.feedback.suggestions && result.feedback.suggestions.length > 0) {
            securityText += `<br>Sugerencias: ${result.feedback.suggestions.join(', ')}`;
        }
        elements.securityInfo.innerHTML = securityText;

        // Verificar coincidencia de contraseñas
        const passwordsMatch = elements.pass1.value === elements.pass2.value;
        const matchAlert = document.getElementById('password-match-alert');
        if (matchAlert) {
            matchAlert.style.display = passwordsMatch ? 'none' : 'block';
        }
        
        // Habilitar el botón de envío solo si se cumplen todos los requisitos, la contraseña no está comprometida y coinciden
        elements.submitBtn.disabled = !(result.allRequirementsMet && !result.hibpCheck && passwordsMatch);
    };

    const checkHIBP = async (password) => {
        try {
            const hashBuffer = await crypto.subtle.digest('SHA-1', new TextEncoder().encode(password));
            const hex = Array.from(new Uint8Array(hashBuffer))
                            .map(b => b.toString(16).padStart(2, '0'))
                            .join('');
            const prefix = hex.substring(0, 5);
            const suffix = hex.substring(5).toUpperCase();
            const response = await fetch(`https://api.pwnedpasswords.com/range/${prefix}`);
            const data = await response.text();
            return data.includes(suffix);
        } catch (error) {
            return false;
        }
    };

    const generatePassword = () => {
        const length = Math.max(10, parseInt(elements.lengthInput.value) || 14);
        const chars = {
            lower: 'abcdefghjkmnpqrstuvwxyz',
            upper: 'ABCDEFGHJKMNPQRSTUVWXYZ',
            number: '23456789',
            special: '!@#$%&*?_'
        };
        
        let password = '';
        // Garantizamos al menos un carácter de cada tipo
        password += chars.lower[Math.floor(Math.random() * chars.lower.length)];
        password += chars.upper[Math.floor(Math.random() * chars.upper.length)];
        password += chars.number[Math.floor(Math.random() * chars.number.length)];
        password += chars.special[Math.floor(Math.random() * chars.special.length)];
        
        const allChars = Object.values(chars).join('');
        for (let i = 4; i < length; i++) {
            password += allChars[Math.floor(Math.random() * allChars.length)];
        }
        
        // Mezclar aleatoriamente
        return password.split('').sort(() => Math.random() - 0.5).join('');
    };

    // Event listeners con debounce para optimizar
    elements.pass1.addEventListener('input', debounce(updateUI, 300));
    elements.pass2.addEventListener('input', updateUI);
    
    document.getElementById('btn-generate').addEventListener('click', () => {
        elements.pass1.value = generatePassword();
        updateUI();
    });

    document.getElementById('btn-use').addEventListener('click', () => {
        elements.pass2.value = elements.pass1.value;
        updateUI();
    });
});

const debounce = (func, wait) => {
    let timeout;
    return (...args) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
};
</script>