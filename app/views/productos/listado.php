<?php
/**
 * Vista: productos/listado.php
 *
 * Variables esperadas:
 * $productos → lista de productos filtrados
 * $categorias → lista de categorías activas
 * $categoriaSeleccionada → id categoría seleccionada (GET)
 */

$title = "Catálogo de Productos";
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container mt-4">

    <div class="row">

        <!-- =============================================================== -->
        <!-- SIDEBAR DE CATEGORÍAS                                          -->
        <!-- =============================================================== -->
        <aside class="col-md-3 mb-4">

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Categorías</h5>
                </div>

                <ul class="list-group list-group-flush">

                    <!-- Opción: TODOS LOS PRODUCTOS -->
                    <a href="<?= BASE_URL ?>/productos" 
                       class="list-group-item list-group-item-action
                              <?= empty($categoriaSeleccionada) ? 'active' : '' ?>">
                        Todos los productos
                    </a>

                    <!-- Categorías activas -->
                    <?php foreach ($categorias as $cat): ?>
                        <a href="<?= BASE_URL ?>/productos?categoria=<?= $cat['id_categoria'] ?>"
                           class="list-group-item list-group-item-action
                                  <?= (int)$categoriaSeleccionada === (int)$cat['id_categoria'] ? 'active' : '' ?>">
                            <?= htmlspecialchars($cat['nombre']) ?>
                        </a>
                    <?php endforeach; ?>

                </ul>
            </div>

        </aside>

        <!-- =============================================================== -->
        <!-- LISTADO DE PRODUCTOS                                            -->
        <!-- =============================================================== -->
        <section class="col-md-9">

            <h3 class="mb-4">
                <?= empty($categoriaSeleccionada)
                    ? 'Todos los productos'
                    : 'Categoría: ' . htmlspecialchars($categorias[array_search($categoriaSeleccionada, array_column($categorias, 'id_categoria'))]['nombre'] ?? '') ?>
            </h3>

            <div class="row">

                <?php if (empty($productos)): ?>

                    <div class="col-12 text-center text-muted">
                        <h5>No hay productos disponibles en esta categoría.</h5>
                    </div>

                <?php else: ?>

                    <?php foreach ($productos as $p): ?>
                        <div class="col-md-4 mb-4">

                            <div class="card shadow-sm h-100">

                                <img src="<?= BASE_URL ?>/uploads/productos/<?= $p['imagen'] ?>"
                                     class="card-img-top" alt="<?= htmlspecialchars($p['nombre']) ?>">

                                <div class="card-body d-flex flex-column">

                                    <h5 class="card-title"><?= htmlspecialchars($p['nombre']) ?></h5>

                                    <p class="card-text text-muted mb-2">
                                        S/ <?= number_format($p['precio'], 2) ?>
                                    </p>

                                    <a href="<?= BASE_URL ?>/productos/detalle/<?= $p['id_producto'] ?>"
                                       class="btn btn-outline-dark mt-auto w-100">
                                        Ver detalle
                                    </a>

                                </div>

                            </div>

                        </div>
                    <?php endforeach; ?>

                <?php endif; ?>

            </div>

        </section>

    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
