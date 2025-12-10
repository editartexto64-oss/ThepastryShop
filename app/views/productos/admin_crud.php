<?php
/**
 * Vista: productos/admin_crud.php
 *
 * Variables esperadas:
 * $productos → lista de productos filtrados
 * $categorias → lista de categorías activas
 * $categoriaSeleccionada → categoría activa (GET)
 */

require_once __DIR__ . '/../layouts/admin_header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">

    <h2 class="fw-bold">Gestión de Productos</h2>

    <!-- BOTÓN CREAR -->
    <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalCrearProducto">
        <i class="bi bi-plus-circle"></i> Nuevo Producto
    </button>

</div>

<!-- =============================================================== -->
<!-- FILTRO POR CATEGORÍA                                           -->
<!-- =============================================================== -->

<div class="card mb-4 shadow-sm">
    <div class="card-body">

        <form method="GET" action="<?= BASE_URL ?>/productos/admin" class="row g-2 align-items-center">

            <div class="col-md-4">
                <label class="form-label fw-bold">Filtrar por categoría:</label>
                <select name="categoria" class="form-select">

                    <option value="">-- Todas las categorías --</option>

                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['id_categoria'] ?>"
                            <?= (int)$categoriaSeleccionada === (int)$cat['id_categoria'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nombre']) ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>

            <div class="col-md-3">
                <button class="btn btn-primary mt-4">
                    <i class="bi bi-filter"></i> Aplicar filtro
                </button>
            </div>

            <?php if (!empty($categoriaSeleccionada)): ?>
                <div class="col-md-3">
                    <a href="<?= BASE_URL ?>/productos/admin" class="btn btn-outline-secondary mt-4">
                        Quitar filtro
                    </a>
                </div>
            <?php endif; ?>

        </form>

    </div>
</div>

<!-- =============================================================== -->
<!-- TABLA DE PRODUCTOS                                              -->
<!-- =============================================================== -->

<div class="table-responsive">
    <table class="table table-bordered table-striped shadow-sm">

        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Imagen</th>
                <th>Producto</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Activo</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>

        <?php foreach ($productos as $p): ?>

            <tr>
                <td><?= $p['id_producto'] ?></td>

                <td>
                    <img src="<?= BASE_URL ?>/uploads/productos/<?= $p['imagen'] ?>"
                         width="60" class="rounded shadow-sm">
                </td>

                <td><?= htmlspecialchars($p['nombre']) ?></td>

                <td><?= htmlspecialchars($p['categoria_nombre']) ?></td>

                <td>S/ <?= number_format($p['precio'], 2) ?></td>

                <td>
                    <?php if ($p['activo']): ?>
                        <span class="badge bg-success">Activo</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Inactivo</span>
                    <?php endif; ?>
                </td>

                <td>
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                            data-bs-target="#modalEditar<?= $p['id_producto'] ?>">
                        <i class="bi bi-pencil-fill"></i>
                    </button>

                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                            data-bs-target="#modalEliminar<?= $p['id_producto'] ?>">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>

            <!-- Aquí irían los MODALES de editar/eliminar que ya hicimos antes -->

        <?php endforeach; ?>

        </tbody>

    </table>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
