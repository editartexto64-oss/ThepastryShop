<?php
/**
 * Vista: auth/register.php
 * Formulario de registro de usuario.
 * - Envía POST a /register
 * - Incluye validaciones HTML5 y token CSRF
 * - Al registrarse se espera que el controlador envíe correo de verificación
 */

use SessionHelper;
use Security;

SessionHelper::start();
$title = "Registro - The PastryShop";
require_once __DIR__ . '/../layouts/header.php';
?>

<!-- Mensajes flash -->
<?php if ($msg = SessionHelper::get('error')): ?>
    <div class="alert alert-danger alert-auto"><?= htmlspecialchars($msg) ?></div>
    <?php SessionHelper::remove('error'); endif; ?>

<?php if ($msg = SessionHelper::get('success')): ?>
    <div class="alert alert-success alert-auto"><?= htmlspecialchars($msg) ?></div>
    <?php SessionHelper::remove('success'); endif; ?>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title mb-3 text-center">Crea tu cuenta</h4>

                <!-- Formulario registro -->
                <form action="<?= BASE_URL ?>/register" method="POST" novalidate>
                    <input type="hidden" name="csrf_token" value="<?= Security::generateToken() ?>">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombres</label>
                            <input type="text" name="nombres" class="form-control" required
                                   value="<?= htmlspecialchars($_POST['nombres'] ?? '') ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Apellidos</label>
                            <input type="text" name="apellidos" class="form-control" required
                                   value="<?= htmlspecialchars($_POST['apellidos'] ?? '') ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Correo electrónico</label>
                            <input type="email" name="correo" class="form-control" required
                                   value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" name="telefono" class="form-control" pattern="\d{9}"
                                   placeholder="9 dígitos" value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Dirección</label>
                            <input type="text" name="direccion" class="form-control"
                                   value="<?= htmlspecialchars($_POST['direccion'] ?? '') ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="contrasena" id="contrasena" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Confirmar contraseña</label>
                            <input type="password" name="contrasena_confirm" class="form-control" required>
                        </div>
                    </div>

                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <small class="text-muted">Al registrarte recibirás un correo para verificar tu cuenta.</small>
                        <button class="btn btn-success">Registrarme</button>
                    </div>
                </form>

                <hr>
                <div class="text-center">
                    ¿Ya tienes cuenta? <a href="<?= BASE_URL ?>/login">Ingresa aquí</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS: pequeña validación para confirmar contraseñas -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    if (!form) return;
    form.addEventListener('submit', function (e) {
        const pwd = document.getElementById('contrasena').value;
        const rpt = form.querySelector('input[name="contrasena_confirm"]').value;
        if (pwd !== rpt) {
            e.preventDefault();
            alert('Las contraseñas no coinciden.');
        }
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
