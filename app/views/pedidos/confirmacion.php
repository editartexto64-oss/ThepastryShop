<?php
/**
 * Vista: pedidos/confirmacion.php
 * - Se muestra después de crear el pedido
 * - Incluye el token para tracking
 *
 * Variable esperada:
 * $pedido (array con id, total, token tracking)
 */

$title = "Pedido confirmado";
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="text-center p-5">

    <h2 class="mb-3 text-success">
        <i class="bi bi-check-circle"></i> ¡Gracias por tu compra!
    </h2>

    <p class="lead">
        Tu pedido ha sido registrado correctamente.
    </p>

    <p>Número de pedido: <strong>#<?= $pedido['id_pedido'] ?></strong></p>

    <p>
        Usa el siguiente código para ver el estado de tu pedido:
    </p>

    <h3 class="fw-bold"><?= $pedido['token'] ?></h3>

    <a href="<?= BASE_URL ?>/tracking" class="btn btn-primary mt-4">
        Ver estado del pedido
    </a>

    <a href="<?= BASE_URL ?>" class="btn btn-outline-dark mt-3">
        Volver al inicio
    </a>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
