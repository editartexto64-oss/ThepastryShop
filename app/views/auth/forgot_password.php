<?php
/**
 * Vista: auth/forgot_password.php
 * Permite solicitar restablecimiento de contraseña.
 * - Envía correo con token (controlador implementa envío).
 * - Form POST a /auth/forgot_password (o la ruta que definas).
 */

use SessionHelper;
use Security;

SessionHelper::start();
$title = "Recuperar contraseña - The PastryShop";
require_once __DIR__ . '/../layouts/header.php';
?>

<?php if ($msg = SessionHelper::get('success')): ?>
    <div class="alert alert-success alert-auto"><?= htmlspecialchars($msg) ?></div>
    <?php SessionHelper::remove('success'); endif; ?>

<?php if ($msg = SessionHelper::get('error')): ?>
    <div class="alert alert-danger alert-auto"><?= htmlspecialchars($msg) ?></div>
    <?php SessionHelper::remove('error'); endif; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title mb-3 text-center">Recuperar contraseña</h4>

                <form action="<?= BASE_URL ?>/auth/forgot_password" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= Security::generateToken() ?>">

                    <div class="mb-3">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" name="correo" class="form-control" required
                               placeholder="tu@correo.com" value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>">
                    </div>

                    <div class="d-flex justify-content-end">
                        <button class="btn btn-warning">Enviar enlace</button>
                    </div>
                </form>

                <hr>
                <div class="text-center">
                    <a href="<?= BASE_URL ?>/login">Volver al inicio</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
