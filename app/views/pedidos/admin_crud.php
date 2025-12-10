<?php
/**
 * Vista: pedidos/admin_crud.php
 * Panel administrador para gestionar pedidos
 *
 * Variables esperadas:
 * $pedidos = lista de pedidos con cliente, total, estado, fecha, token
 */

require_once __DIR__ . '/../layouts/admin_header.php';
?>

<h2>Gestión de Pedidos</h2>

<div class="table-responsive mt-3">

    <table class="table table-bordered table-striped shadow-sm">

        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Total</th>
            <th>Estado</th>
            <th>Fecha</th>
            <th>Token</th>
            <th>Acciones</th>
        </tr>
        </thead>

        <tbody>

        <?php foreach ($pedidos as $p): ?>

            <tr>
                <td><?= $p['id_pedido'] ?></td>
                <td><?= htmlspecialchars($p['cliente']) ?></td>
                <td>S/ <?= number_format($p['total'], 2) ?></td>
                <td>
                    <span class="badge bg-info"><?= $p['estado'] ?></span>
                </td>
                <td><?= $p['fecha'] ?></td>
                <td><?= $p['token'] ?></td>

                <td>
                    <!-- BOTÓN ESTADO -->
                    <button class="btn btn-warning btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#modalEstado<?= $p['id_pedido'] ?>">
                        Cambiar estado
                    </button>
                </td>
            </tr>

            <!-- MODAL ESTADO -->
            <div class="modal fade" id="modalEstado<?= $p['id_pedido'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <form action="<?= BASE_URL ?>/pedidos/estado" method="POST">

                            <input type="hidden" name="id_pedido" value="<?= $p['id_pedido'] ?>">

                            <div class="modal-header bg-warning">
                                <h5 class="modal-title">Actualizar estado</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <select name="estado" class="form-select" required>
                                    <option value="Recibido">Recibido</option>
                                    <option value="En preparación">En preparación</option>
                                    <option value="En camino">En camino</option>
                                    <option value="Entregado">Entregado</option>
                                </select>

                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-success">Guardar</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

        <?php endforeach; ?>

        </tbody>

    </table>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
