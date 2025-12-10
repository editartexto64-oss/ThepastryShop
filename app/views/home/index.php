<?php
/**
 * Vista: home/index.php
 * Página principal del sitio.
 * - Muestra banner principal
 * - Lista productos destacados
 * - Imágenes de productos en cards Bootstrap
 * - Botón para ver más detalles
 * - Botón de agregar al carrito
 *
 * NOTA: Esta vista espera que el controlador HomeController pase:
 * $productos = lista de productos activos del catálogo
 * $categorias = lista de categorías activas
 */

$title = "Inicio - The PastryShop";
require_once __DIR__ . '/../layouts/header.php';
?>

<!-- ===================== -->
<!-- BANNER PRINCIPAL      -->
<!-- ===================== -->

<div class="p-5 mb-4 rounded-3" style="background: linear-gradient(135deg, #f8c9dd, #f2a5c3);">
    <div class="container py-5 text-center text-white">
        <h1 class="display-4 fw-bold">Bienvenido a The PastryShop</h1>
        <p class="fs-5">
            Pasteles hechos a mano, con amor y los mejores ingredientes.  
            ✨ ¡Ordena hoy y endulza tu día! ✨
        </p>
        <a href="<?= BASE_URL ?>/productos" class="btn btn-dark btn-lg">
            Ver catálogo <i class="bi bi-arrow-right-circle"></i>
        </a>
    </div>
</div>

<!-- ===================== -->
<!-- CATEGORÍAS            -->
<!-- ===================== -->

<?php if (!empty($categorias)): ?>
    <h3 class="mb-3 text-center">Categorías</h3>

    <div class="row mb-5 justify-content-center">

        <?php foreach ($categorias as $cat): ?>
            <div class="col-md-3 mb-3">
                <a href="<?= BASE_URL ?>/productos?categoria=<?= $cat['id_categoria'] ?>"
                   class="text-decoration-none text-dark">

                    <div class="card shadow-sm border-0 text-center p-3"
                         style="background:#fce7f2; border-radius:20px;">

                        <h5 class="fw-bold"><?= htmlspecialchars($cat['nombre']) ?></h5>

                    </div>

                </a>
            </div>
        <?php endforeach; ?>

    </div>
<?php endif; ?>

<!-- ===================== -->
<!-- PRODUCTOS DESTACADOS  -->
<!-- ===================== -->

<h3 class="mb-4 text-center">Productos Destacados</h3>

<div class="row">

    <?php if (!empty($productos)): ?>
        <?php foreach ($productos as $p): ?>

            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">

                    <!-- Imagen -->
                    <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($p['imagen']) ?>"
                         class="card-img-top"
                         alt="Imagen de pastel"
                         style="height:260px; object-fit:cover;">

                    <div class="card-body">

                        <!-- Nombre -->
                        <h5 class="card-title fw-bold"><?= htmlspecialchars($p['nombre']) ?></h5>

                        <!-- Descripción breve -->
                        <p class="card-text text-muted" style="font-size: 14px;">
                            <?= htmlspecialchars(substr($p['descripcion'], 0, 100)) ?>...
                        </p>

                        <!-- Precio -->
                        <p class="fw-bold fs-5">
                            S/ <?= number_format($p['precio'], 2) ?>
                        </p>

                        <!-- Botones -->
                        <div class="d-flex justify-content-between">
                            <a href="<?= BASE_URL ?>/productos/detalle/<?= $p['id_producto'] ?>"
                               class="btn btn-outline-primary btn-sm">
                                Ver más
                            </a>

                            <!-- Formulario para agregar al carrito -->
                            <form action="<?= BASE_URL ?>/carrito/agregar" method="POST">
                                <input type="hidden" name="id_producto" value="<?= $p['id_producto'] ?>">
                                <button class="btn btn-dark btn-sm">
                                    <i class="bi bi-cart-plus"></i> Añadir
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <div class="col-12 text-center">
            <p class="text-muted">No hay productos disponibles por el momento.</p>
        </div>

    <?php endif; ?>

</div>

<!-- ===================== -->
<!-- SECCIÓN FINAL         -->
<!-- ===================== -->

<div class="text-center mt-5 p-4" style="background:#fbe9f3; border-radius:15px;">
    <h4 class="fw-bold mb-3">¿Necesitas un pastel personalizado?</h4>
    <p class="mb-3">
        Podemos crear el diseño perfecto para tu evento.  
        Solo cuéntanos tu idea 🎂✨
    </p>

    <a href="<?= BASE_URL ?>/productos/personalizado" class="btn btn-lg btn-primary">
        Solicitar diseño personalizado
    </a>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
