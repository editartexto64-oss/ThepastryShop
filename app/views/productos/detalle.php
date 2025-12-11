<?php
/**
 * Vista: productos/detalle.php
 * - Muestra detalle completo del producto
 * - Imagen grande + info + botón agregar
 * - Compatible con vista responsive
 *
 * Variables esperadas:
 * $producto = datos del producto seleccionado
 */
$title = $producto['nombre'] . " | ThePastryShop";
$description = "Compra el pastel " . $producto['nombre'] . " por S/ " . $producto['precio'] . ". " . $producto['descripcion'];

$title = $producto['nombre'] . " - The PastryShop";
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="row">

    <!-- IMAGEN PRINCIPAL -->
    <div class="col-md-6 mb-3">
        <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($producto['imagen']) ?>"
             class="img-fluid rounded shadow"
             style="object-fit:cover;">
    </div>

    <!-- INFORMACIÓN -->
    <div class="col-md-6">

        <h2 class="fw-bold"><?= htmlspecialchars($producto['nombre']) ?></h2>

        <p class="text-muted"><?= nl2br(htmlspecialchars($producto['descripcion'])) ?></p>

        <h3 class="fw-bold text-success">S/ <?= number_format($producto['precio'], 2) ?></h3>

        <hr>

        <!-- BOTÓN AGREGAR AL CARRITO -->
        <form action="<?= BASE_URL ?>/carrito/agregar" method="POST" class="mt-3">

            <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>">

            <!-- Cantidad -->
            <label class="form-label fw-bold">Cantidad</label>
            <input type="number" name="cantidad" class="form-control mb-3"
                   required min="1" value="1">

            <button class="btn btn-dark btn-lg w-100">
                <i class="bi bi-cart-plus"></i> Agregar al carrito
            </button>

        </form>

        <hr>

        <!-- SECCIÓN DE RESEÑAS DEMÁS (si las deseas mostrar aquí) -->
    </div>

</div>
<!-- Schema.org de producto -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Product",
  "name": "<?= $producto['nombre'] ?>",
  "image": "<?= BASE_URL . 'public/uploads/' . $producto['imagen'] ?>",
  "description": "<?= $producto['descripcion'] ?>",
  "sku": "<?= $producto['id'] ?>",
  "offers": {
    "@type": "Offer",
    "priceCurrency": "PEN",
    "price": "<?= $producto['precio'] ?>",
    "availability": "https://schema.org/InStock"
  }
}
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
