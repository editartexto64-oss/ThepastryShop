<?php
/**
 * Vista: pedidos/admin_tracking.php
 *
 * Muestra y permite actualizar el estado del pedido para el administrador.
 *
 * Variables recibidas:
 * $pedido → datos básicos del pedido (id, cliente, total)
 * $tracking → id_pedido, estado, token, fecha
 */

require_once __DIR__ . '/../layouts/admin_header.php';
?>

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Tracking del Pedido</h2>

        <a href="<?= BASE_URL ?>/pedidos/admin" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <!-- ============================== -->
    <!--   INFORMACIÓN DEL PEDIDO       -->
    <!-- ============================== -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">

            <h4 class="fw-bold mb-3">Información del Pedido</h4>

            <p><strong>ID Pedido:</strong> #<?= $pedido['id_pedido'] ?></p>
            <p><strong>Cliente:</strong> <?= htmlspecialchars($pedido['cliente']) ?></p>
            <p><strong>Total:</strong> S/ <?= number_format($pedido['total'], 2) ?></p>

            <hr>

            <p><strong>Token de tracking:</strong> 
                <span class="badge bg-primary"><?= htmlspecialchars($tracking['token']) ?></span>
            </p>

            <p><strong>Última actualización:</strong> <?= $tracking['fecha'] ?></p>

        </div>
    </div>

    <!-- ============================== -->
    <!--     ESTADOS DEL PEDIDO         -->
    <!-- ============================== -->

    <div class="card mb-4 shadow-sm">
        <div class="card-body">

            <h4 class="fw-bold mb-3">Estado del pedido</h4>

            <?php
            $estadoActual = strtolower($tracking['estado']);

            function activo($num, $estadoActual) {
                $orden = [
                    "recibido" => 1,
                    "en preparación" => 2,
                    "en camino" => 3,
                    "entregado" => 4
                ];
                return $orden[$estadoActual] >= $num;
            }
            ?>

            <!-- TIMELINE ADMIN -->
            <div class="timeline-tracking-admin mb-4">

                <div class="row text-center mb-4">

                    <div class="col">
                        <div class="circle <?= activo(1, $estadoActual) ? 'active' : '' ?>"></div>
                        <p class="mt-2">Recibido</p>
                    </div>

                    <div class="col">
                        <div class="circle <?= activo(2, $estadoActual) ? 'active' : '' ?>"></div>
                        <p class="mt-2">En preparación</p>
                    </div>

                    <div class="col">
                        <div class="circle <?= activo(3, $estadoActual) ? 'active' : '' ?>"></div>
                        <p class="mt-2">En camino</p>
                    </div>

                    <div class="col">
                        <div class="circle <?= activo(4, $estadoActual) ? 'active' : '' ?>"></div>
                        <p class="mt-2">Entregado</p>
                    </div>

                </div>

                <!-- Línea gris -->
                <style>
                    .timeline-tracking-admin {
                        position: relative;
                        padding: 20px;
                    }
                    .timeline-tracking-admin .circle {
                        width: 28px;
                        height: 28px;
                        border-radius: 50%;
                        background: #cfcfcf;
                        margin: auto;
                    }
                    .timeline-tracking-admin .circle.active {
                        background: #198754;
                        box-shadow: 0 0 8px rgba(0,0,0,0.2);
                    }
                    .timeline-tracking-admin .row:before {
                        content: "";
                        position: absolute;
                        top: 34px;
                        left: 10%;
                        width: 80%;
                        height: 4px;
                        background: #d5d5d5;
                        z-index: -1;
                    }
                </style>

            </div>

            <!-- FORMULARIO ACTUALIZAR ESTADO -->
            <form action="<?= BASE_URL ?>/tracking/actualizar" method="POST">

                <input type="hidden" name="id_pedido" value="<?= $pedido['id_pedido'] ?>">

                <label class="form-label fw-bold">Actualizar estado:</label>

                <select name="estado" class="form-select" required>
                    <option value="Pedido Recibido" <?= $tracking['estado'] === "Pedido Recibido" ? 'selected' : '' ?>>Pedido Recibido</option>
                    <option value="Confirmado"<?= $tracking['estado'] === "Confirmado" ? 'selected' : '' ?>>Confirmado</option>
                    <option value="En preparación"<?= $tracking['estado'] === "En preparación" ? 'selected' : '' ?>>En preparación</option>
                    <option value="En camino"     <?= $tracking['estado'] === "En camino" ? 'selected' : '' ?>>En camino</option>
                    <option value="Entregado"     <?= $tracking['estado'] === "Entregado" ? 'selected' : '' ?>>Entregado</option>
                </select>

                <button class="btn btn-success mt-3">
                    <i class="bi bi-check-circle"></i> Guardar cambios
                </button>

            </form>

        </div> 
    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
