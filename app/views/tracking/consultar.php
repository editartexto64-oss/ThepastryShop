<?php
/**
 * Vista: tracking/consultar.php
 *
 * Dependencias esperadas desde el controlador:
 * 
 * $token         -> token ingresado por el usuario (opcional)
 * $trackingData  -> datos del pedido: estado, fecha, id_pedido
 * $error         -> mensaje de error (si el token no existe)
 */

$title = "Seguimiento de pedido";
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container mt-5">

    <!-- ====================================== -->
    <!--        FORMULARIO BUSCAR TOKEN         -->
    <!-- ====================================== -->

    <div class="card shadow mb-4">
        <div class="card-body">

            <h3 class="mb-3 text-center fw-bold">
                Seguimiento de tu pedido
            </h3>

            <p class="text-center text-muted">
                Ingresa el código de seguimiento que recibiste al confirmar tu compra.
            </p>

            <form action="<?= BASE_URL ?>/tracking/buscar" method="POST" class="mt-4">

                <div class="input-group input-group-lg">
                    <input type="text"
                           maxlength="40"
                           name="token"
                           required
                           class="form-control"
                           placeholder="Ej: PSTRY-ABC123XYZ"
                           value="<?= htmlspecialchars($token ?? '') ?>">
                    <button class="btn btn-dark" type="submit">
                        Buscar <i class="bi bi-search"></i>
                    </button>
                </div>

            </form>

        </div>
    </div>

    <!-- ====================================== -->
    <!--     MENSAJE DE ERROR SI NO EXISTE      -->
    <!-- ====================================== -->

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- ====================================== -->
    <!--    RESULTADO DEL TRACKING SI EXISTE     -->
    <!-- ====================================== -->

    <?php if (!empty($trackingData)): ?>

        <div class="card shadow p-4">

            <h4 class="fw-bold text-center mb-3">
                Estado de tu pedido
            </h4>

            <p class="text-center">
                Número de pedido:
                <strong>#<?= htmlspecialchars($trackingData['id_pedido']) ?></strong>
            </p>

            <p class="text-center mb-4">
                Seguimiento:
                <span class="badge bg-primary"><?= htmlspecialchars($token) ?></span>
            </p>

            <!-- ====================================== -->
            <!--     TIMELINE DE ESTADOS DEL PEDIDO     -->
            <!-- ====================================== -->

            <?php
            /*
             * Estados posibles:
             * 1. Recibido
             * 2. En preparación
             * 3. En camino
             * 4. Entregado
             *
             * Determinamos hasta dónde pintar la línea de progreso
             */

            $estado = strtolower($trackingData['estado']);

            function estadoActivo($n, $estado)
            {
                $ordenEstados = [
                    'recibido' => 1,
                    'en preparación' => 2,
                    'en camino' => 3,
                    'entregado' => 4
                ];

                return ($ordenEstados[$estado] ?? 0) >= $n;
            }
            ?>

            <div class="timeline-tracking">

                <!-- Cada punto del timeline -->
                <div class="row text-center mb-4">

                    <!-- RECIBIDO -->
                    <div class="col">
                        <div class="circle <?= estadoActivo(1, $estado) ? 'active' : '' ?>"></div>
                        <p class="mt-2">Recibido</p>
                    </div>

                    <!-- EN PREPARACIÓN -->
                    <div class="col">
                        <div class="circle <?= estadoActivo(2, $estado) ? 'active' : '' ?>"></div>
                        <p class="mt-2">En preparación</p>
                    </div>

                    <!-- EN CAMINO -->
                    <div class="col">
                        <div class="circle <?= estadoActivo(3, $estado) ? 'active' : '' ?>"></div>
                        <p class="mt-2">En camino</p>
                    </div>

                    <!-- ENTREGADO -->
                    <div class="col">
                        <div class="circle <?= estadoActivo(4, $estado) ? 'active' : '' ?>"></div>
                        <p class="mt-2">Entregado</p>
                    </div>

                </div>

                <!-- Línea de progreso -->
                <style>
                    .timeline-tracking {
                        position: relative;
                        padding: 20px;
                    }

                    .timeline-tracking .circle {
                        width: 28px;
                        height: 28px;
                        border-radius: 50%;
                        background: #d5d5d5;
                        margin: auto;
                    }

                    .timeline-tracking .circle.active {
                        background: #198754;
                        box-shadow: 0 0 8px rgba(0,0,0,0.2);
                    }

                    .timeline-tracking .row:before {
                        content: "";
                        position: absolute;
                        top: 34px;
                        left: 10%;
                        width: 80%;
                        height: 4px;
                        background: #d5d5d5;
                        z-index: -1;
                    }

                    .timeline-tracking .circle.active ~ .line {
                        background: #198754;
                    }
                </style>

            </div>

            <p class="text-center mt-4 fs-5">
                Estado actual: <strong><?= ucfirst($trackingData['estado']) ?></strong>
            </p>

            <p class="text-center text-muted">
                Última actualización: <?= $trackingData['fecha'] ?>
            </p>

            <div class="text-center mt-4">
                <a href="<?= BASE_URL ?>" class="btn btn-outline-dark">
                    Volver al inicio
                </a>
            </div>

        </div>

    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
