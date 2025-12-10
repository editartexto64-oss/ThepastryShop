<?php
/**
 * Vista: errores/500.php
 * Página mostrada cuando ocurre un error interno en el servidor.
 */

$title = "Error interno del servidor - 500";
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container text-center py-5">

    <h1 class="display-1 fw-bold text-danger">
        500
    </h1>

    <i class="bi bi-gear-fill text-secondary" style="font-size: 4rem;"></i>

    <h2 class="mt-4 fw-bold">Error interno</h2>

    <p class="text-muted mb-4">
        Ocurrió un problema interno mientras procesábamos tu solicitud.<br>
        Nuestro equipo ya está trabajando para solucionarlo.
    </p>

    <a href="<?= BASE_URL ?>" class="btn btn-dark btn-lg">
        Volver al inicio
    </a>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
