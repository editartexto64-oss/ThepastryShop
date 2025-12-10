<?php
/**
 * Vista: cupones/admin_crud.php
 * CRUD completo de cupones
 *
 * Variables esperadas:
 * $cupones -> Lista de cupones
 */

require_once __DIR__ . '/../layouts/admin_header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Gestión de Cupones</h2>

    <!-- BOTÓN CREAR CUPÓN -->
    <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalCrearCupon">
        <i class="bi bi-plus-lg"></i> Nuevo Cupón
    </button>
</div>

<!-- ========================= -->
<!-- TABLA DE CUPONES         -->
<!-- ========================= -->

<div class="table-responsive">
    <table class="table table-striped table-bordered shadow-sm">

        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Código</th>
            <th>Descuento (%)</th>
            <th>Fecha Expiración</th>
            <th>Usos Totales</th>
            <th>Usos Permitidos</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        </thead>

        <tbody>

        <?php foreach ($cupones as $c): ?>
            <tr>
                <td><?= $c['id_cupon'] ?></td>
                <td><?= htmlspecialchars($c['codigo']) ?></td>
                <td><?= $c['descuento'] ?>%</td>
                <td><?= $c['fecha_expiracion'] ?></td>
                <td><?= $c['usos_totales'] ?></td>
                <td><?= $c['limite_usos'] ?></td>
                <td>
                    <?php if ($c['activo']): ?>
                        <span class="badge bg-success">Activo</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Inactivo</span>
                    <?php endif; ?>
                </td>

                <td>
                    <!-- EDITAR -->
                    <button
                        class="btn btn-warning btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#modalEditar<?= $c['id_cupon'] ?>">
                        <i class="bi bi-pencil-square"></i>
                    </button>

                    <!-- ELIMINAR -->
                    <button
                        class="btn btn-danger btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#modalEliminar<?= $c['id_cupon'] ?>">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>

            <!-- ========================= -->
            <!-- MODAL EDITAR CUPÓN        -->
            <!-- ========================= -->
            <div class="modal fade" id="modalEditar<?= $c['id_cupon'] ?>" tabindex="-1">

                <div class="modal-dialog">
                    <div class="modal-content">

                        <form action="<?= BASE_URL ?>/cupones/editar" method="POST">

                            <input type="hidden" name="id_cupon" value="<?= $c['id_cupon'] ?>">

                            <div class="modal-header bg-warning">
                                <h5 class="modal-title">Editar Cupón</h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <label class="form-label">Código</label>
                                <input type="text" class="form-control"
                                       name="codigo" maxlength="20"
                                       value="<?= htmlspecialchars($c['codigo']) ?>" required>

                                <label class="form-label mt-3">Descuento (%)</label>
                                <input type="number" class="form-control"
                                       name="descuento" min="1" max="90"
                                       value="<?= $c['descuento'] ?>" required>

                                <label class="form-label mt-3">Fecha Expiración</label>
                                <input type="date" class="form-control"
                                       name="fecha_expiracion"
                                       value="<?= $c['fecha_expiracion'] ?>" required>

                                <label class="form-label mt-3">Límite de Usos</label>
                                <input type="number" class="form-control"
                                       name="limite_usos" min="1"
                                       value="<?= $c['limite_usos'] ?>" required>

                                <label class="form-label mt-3">Estado</label>
                                <select class="form-select" name="activo">
                                    <option value="1" <?= $c['activo'] ? 'selected' : '' ?>>Activo</option>
                                    <option value="0" <?= !$c['activo'] ? 'selected' : '' ?>>Inactivo</option>
                                </select>

                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-success">Guardar cambios</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

            <!-- ========================= -->
            <!-- MODAL ELIMINAR CUPÓN     -->
            <!-- ========================= -->
            <div class="modal fade" id="modalEliminar<?= $c['id_cupon'] ?>" tabindex="-1">

                <div class="modal-dialog modal-sm">
                    <div class="modal-content">

                        <form action="<?= BASE_URL ?>/cupones/eliminar" method="POST">

                            <input type="hidden" name="id_cupon" value="<?= $c['id_cupon'] ?>">

                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">Eliminar Cupón</h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                ¿Seguro que quieres eliminar este cupón?
                                <br><br>
                                <strong><?= htmlspecialchars($c['codigo']) ?></strong>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button class="btn btn-danger">Eliminar</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

        <?php endforeach; ?>

        </tbody>

    </table>
</div>

<!-- ====================================== -->
<!-- MODAL CREAR CUPÓN                      -->
<!-- ====================================== -->

<div class="modal fade" id="modalCrearCupon" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="<?= BASE_URL ?>/cupones/crear" method="POST">

                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">Crear nuevo cupón</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <label class="form-label">Código</label>
                    <input type="text" class="form-control"
                           name="codigo" maxlength="20" required>

                    <label class="form-label mt-3">Descuento (%)</label>
                    <input type="number" class="form-control"
                           name="descuento" min="1" max="90" required>

                    <label class="form-label mt-3">Fecha Expiración</label>
                    <input type="date" class="form-control"
                           name="fecha_expiracion" required>

                    <label class="form-label mt-3">Límite de Usos</label>
                    <input type="number" class="form-control"
                           name="limite_usos" min="1" required>

                    <label class="form-label mt-3">Estado</label>
                    <select class="form-select" name="activo">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Crear cupón</button>
                </div>

            </form>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
