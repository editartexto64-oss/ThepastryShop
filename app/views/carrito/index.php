<?php
/**
 * Vista: carrito/index.php
 * Muestra el carrito de compras con:
 * - Tabla de productos agregados
 * - Botones para actualizar cantidad
 * - Botones para eliminar
 * - Resumen de totales
 * - Acción para ir al checkout
 *
 * Variables esperadas desde el controlador:
 * $carrito = array con productos añadidos
 *
 * Cada item debe tener:
 * id_producto, nombre, precio, cantidad, imagen
 */

$title = "Carrito de Compras - The PastryShop";
require_once __DIR__ . '/../layouts/header.php';
?>

<h2 class="mb-4 text-center"><i class="bi bi-cart"></i> Mi Carrito</h2>

<?php if (empty($carrito)): ?>

    <!-- Carrito vacío -->
    <div class="text-center p-5">
        <h4 class="text-muted mb-3">Tu carrito está vacío</h4>
        <a href="<?= BASE_URL ?>/productos" class="btn btn-primary btn-lg">
            Ver productos
        </a>
    </div>

<?php else: ?>

    <div class="row">

        <!-- ================================ -->
        <!-- TABLA DEL CARRITO               -->
        <!-- ================================ -->
        <div class="col-lg-8 mb-4">

            <div class="table-responsive shadow-sm">
                <table class="table align-middle">

                    <thead class="table-dark">
                    <tr>
                        <th width="80">Imagen</th>
                        <th>Producto</th>
                        <th width="120">Precio</th>
                        <th width="120">Cantidad</th>
                        <th width="120">Subtotal</th>
                        <th width="100">Eliminar</th>
                    </tr>
                    </thead>

                    <tbody>

                    <?php
                    $total = 0;
                    foreach ($carrito as $item):
                        $subtotal = $item['precio'] * $item['cantidad'];
                        $total += $subtotal;
                    ?>

                        <tr>

                            <!-- IMAGEN -->
                            <td>
                                <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($item['imagen']) ?>"
                                     width="70" height="60"
                                     style="object-fit:cover;"
                                     class="rounded">
                            </td>

                            <!-- NOMBRE -->
                            <td>
                                <strong><?= htmlspecialchars($item['nombre']) ?></strong>
                            </td>

                            <!-- PRECIO -->
                            <td>
                                S/ <?= number_format($item['precio'], 2) ?>
                            </td>

                            <!-- CANTIDAD (FORM PARA ACTUALIZAR) -->
                            <td>
                                <form action="<?= BASE_URL ?>/carrito/actualizar" method="POST" class="d-flex">
                                    <input type="hidden" name="id_producto" value="<?= $item['id_producto'] ?>">

                                    <input type="number"
                                           name="cantidad"
                                           value="<?= $item['cantidad'] ?>"
                                           min="1"
                                           class="form-control form-control-sm">

                                    <button class="btn btn-sm btn-success ms-2">
                                        <i class="bi bi-check2"></i>
                                    </button>
                                </form>
                            </td>

                            <!-- SUBTOTAL -->
                            <td class="fw-bold">
                                S/ <?= number_format($subtotal, 2) ?>
                            </td>

                            <!-- BOTÓN ELIMINAR -->
                            <td>
                                <form action="<?= BASE_URL ?>/carrito/eliminar" method="POST">
                                    <input type="hidden" name="id_producto" value="<?= $item['id_producto'] ?>">
                                    <button class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>
            </div>

        </div>

        <!-- ================================ -->
        <!-- RESUMEN DEL CARRITO             -->
        <!-- ================================ -->
        <div class="col-lg-4">

            <div class="card shadow-sm">
                <div class="card-body">

                    <h4 class="mb-3">Resumen del pedido</h4>

                    <p class="d-flex justify-content-between">
                        <span>Subtotal:</span>
                        <strong>S/ <?= number_format($total, 2) ?></strong>
                    </p>

                    <!-- Si deseas agregar costo de envío aquí -->
                    <p class="d-flex justify-content-between">
                        <span>Envío:</span>
                        <strong>Gratis</strong>
                    </p>

                    <hr>

                    <p class="d-flex justify-content-between fs-5">
                        <span>Total:</span>
                        <strong>S/ <?= number_format($total, 2) ?></strong>
                    </p>

                    <!-- BOTÓN IR A CHECKOUT -->
                    <a href="<?= BASE_URL ?>/pedidos/checkout" class="btn btn-dark w-100 btn-lg mt-3">
                        Continuar con la compra <i class="bi bi-arrow-right-circle"></i>
                    </a>

                    <!-- BOTÓN SEGUIR COMPRANDO -->
                    <a href="<?= BASE_URL ?>/productos" class="btn btn-outline-secondary w-100 mt-3">
                        Seguir comprando
                    </a>

                </div>
            </div>

        </div>

    </div>

<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
