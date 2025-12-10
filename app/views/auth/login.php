<?php
/**
 * Vista: auth/login.php
 * Formulario de inicio de sesión.
 * - Usa layout header/footer
 * - Muestra mensajes flash (success/error)
 * - Envía POST a la ruta 'login'
 * - Incluye token CSRF mediante Security::generateToken()
 */



SessionHelper::start(); // Asegura que la sesión esté activa
$title = "Ingresar - The PastryShop";
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
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title mb-3 text-center">Iniciar sesión</h4>

                <!-- Formulario POST a /login -->
                <form action="<?= BASE_URL ?>/login" method="POST" novalidate>
                    <!-- CSRF token -->
                    <input type="hidden" name="csrf_token" value="<?= Security::generateToken() ?>">

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo electrónico</label>
                        <input type="email" name="correo" id="correo" class="form-control" required
                               placeholder="tu@correo.com" value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>">
                        <div class="form-text">Usa el correo con el que te registraste.</div>
                    </div>

                    <!-- Contraseña -->
                    <div class="mb-3">
                        <label for="contrasena" class="form-label">Contraseña</label>
                        <input type="password" name="contrasena" id="contrasena" class="form-control" required>
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="<?= BASE_URL ?>/auth/forgot_password" class="small">¿Olvidaste tu contraseña?</a>
                        <button class="btn btn-primary">Ingresar</button>
                    </div>
                </form>

                <hr>

                <div class="text-center">
                    ¿No tienes cuenta? <a href="<?= BASE_URL ?>/register">Regístrate aquí</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
