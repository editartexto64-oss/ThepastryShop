<?php
/**
 * Vista: categorias/admin_crud.php
 *
 * Variables esperadas:
 * $categorias -> lista de categorías con:
 *   id_categoria, nombre, descripcion, imagen, activo
 */

require_once __DIR__ . '/../layouts/admin_header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Gestión de Categorías</h2>

    <!-- Botón para crear nueva categoría -->
    <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalCrear">
        <i class="bi bi-plus-circle"></i> Nueva Categoría
    </button>
</div>

<!-- ============================ -->
<!-- TABLA DE LISTADO DE CATEGORIAS -->
<!-- ============================ -->

<div class="table-responsive">
    <table class="table table-bordered table-striped shadow-sm">

        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Imagen</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        </thead>

        <tbody>

        <?php foreach ($categorias as $cat): ?>
            <tr>

                <td><?= $cat['id_categoria'] ?></td>

                <td><?= htmlspecialchars($cat['nombre']) ?></td>

                <td><?= htmlspecialchars($cat['descripcion']) ?></td>

                <td>
                    <?php if (!empty($cat['imagen'])): ?>
                        <img src="<?= BASE_URL ?>/uploads/categorias/<?= $cat['imagen'] ?>"
                             width="60" class="rounded shadow-sm">
                    <?php else: ?>
                        <span class="text-muted">Sin imagen</span>
                    <?php endif; ?>
                </td>

                <td>
                    <?php if ($cat['activo']): ?>
                        <span class="badge bg-success">Activo</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Inactivo</span>
                    <?php endif; ?>
                </td>

                <td>

                    <!-- BOTÓN EDITAR -->
                    <button class="btn btn-warning btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#modalEditar<?= $cat['id_categoria'] ?>">
                        <i class="bi bi-pencil-fill"></i>
                    </button>

                    <!-- BOTÓN BORRAR -->
                    <button class="btn btn-danger btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#modalEliminar<?= $cat['id_categoria'] ?>">
                        <i class="bi bi-trash"></i>
                    </button>

                </td>

            </tr>

            <!-- ========================= -->
            <!-- MODAL: EDITAR CATEGORÍA   -->
            <!-- ========================= -->
            <div class="modal fade" id="modalEditar<?= $cat['id_categoria'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <form action="<?= BASE_URL ?>/categorias/editar" method="POST" enctype="multipart/form-data">

                            <input type="hidden" name="id_categoria" value="<?= $cat['id_categoria'] ?>">

                            <div class="modal-header bg-warning">
                                <h5 class="modal-title">Editar Categoría</h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <label class="form-label">Nombre</label>
                                <input type="text" name="nombre" maxlength="80" required
                                       class="form-control"
                                       value="<?= htmlspecialchars($cat['nombre']) ?>">

                                <label class="form-label mt-3">Descripción</label>
                                <textarea name="descripcion" rows="3" maxlength="255"
                                          class="form-control"><?= htmlspecialchars($cat['descripcion']) ?></textarea>

                                <label class="form-label mt-3">Imagen (opcional)</label>
                                <input type="file" name="imagen" class="form-control">

                                <?php if (!empty($cat['imagen'])): ?>
                                    <small class="text-muted">Imagen actual: <?= $cat['imagen'] ?></small>
                                <?php endif; ?>

                                <label class="form-label mt-3">Estado</label>
                                <select class="form-select" name="activo">
                                    <option value="1" <?= $cat['activo'] ? 'selected' : '' ?>>Activo</option>
                                    <option value="0" <?= !$cat['activo'] ? 'selected' : '' ?>>Inactivo</option>
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
            <!-- MODAL: ELIMINAR CATEGORÍA -->
            <!-- ========================= -->
            <div class="modal fade" id="modalEliminar<?= $cat['id_categoria'] ?>" tabindex="-1">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">

                        <form action="<?= BASE_URL ?>/categorias/eliminar" method="POST">

                            <input type="hidden" name="id_categoria" value="<?= $cat['id_categoria'] ?>">

                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">Eliminar Categoría</h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                ¿Eliminar esta categoría?<br>
                                <strong><?= htmlspecialchars($cat['nombre']) ?></strong>
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

<!-- ========================= -->
<!-- MODAL: CREAR CATEGORÍA    -->
<!-- ========================= -->

<div class="modal fade" id="modalCrear" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="<?= BASE_URL ?>/categorias/crear" method="POST" enctype="multipart/form-data">

                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">Nueva Categoría</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" maxlength="80" required class="form-control">

                    <label class="form-label mt-3">Descripción</label>
                    <textarea name="descripcion" rows="3" maxlength="255" class="form-control"></textarea>

                    <label class="form-label mt-3">Imagen (opcional)</label>
                    <input type="file" name="imagen" class="form-control">

                    <label class="form-label mt-3">Estado</label>
                    <select class="form-select" name="activo">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Crear Categoría</button>
                </div>

            </form>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
