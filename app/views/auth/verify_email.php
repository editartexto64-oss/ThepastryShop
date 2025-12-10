<?php
/**
 * Vista: auth/verify_email.php
 * Página que informa al usuario sobre la verificación de correo.
 * - Se muestra tras el registro o al seguir enlace con token.
 * - El controlador debe comprobar el token y actualizar el estado 'verificado'.
 */

use SessionHelper;

SessionHelper::start();
$title = "Verificar correo - The PastryShop";
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h4 class="mb-3">Verificación de correo</h4>

                <?php if ($msg = SessionHelper::get('success')): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($msg) ?></div>
                    <?php SessionHelper::remove('success'); endif; ?>

                <?php if ($msg = SessionHelper::get('error')): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($msg) ?></div>
                    <?php SessionHelper::remove('error'); endif; ?>

                <p>
                    Para completar tu registro revisa tu bandeja de entrada y haz clic en el enlace
                    de verificación que te enviamos. Si no recibiste el correo, revisa la carpeta de spam
                    o solicita reenvío desde el panel de ayuda.
                </p>

                <a href="<?= BASE_URL ?>/login" class="btn btn-primary">Ir al inicio</a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
