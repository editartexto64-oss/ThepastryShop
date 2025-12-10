<?php
/**
 * Vista: errores/404.php
 * Página mostrada cuando no se encuentra una ruta o recurso.
 */

$title = "Página no encontrada - Error 404";
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container text-center py-5">

    <h1 class="display-1 fw-bold text-danger">
        404
    </h1>

    <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 4rem;"></i>

    <h2 class="mt-4 fw-bold">Página no encontrada</h2>

    <p class="text-muted mb-4">
        Lo sentimos, no pudimos encontrar la página que estás buscando.<br>
        Es posible que haya sido movida, eliminada o que la URL no exista.
    </p>

    <a href="<?= BASE_URL ?>" class="btn btn-dark btn-lg">
        Volver al inicio
    </a>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
