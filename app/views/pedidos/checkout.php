<?php
/**
 * Vista: pedidos/checkout.php
 * - Formulario de compra estilo pastel premium
 * - Muestra datos del cliente, dirección, cupón y resumen
 * - Último paso antes de generar pedido
 *
 * Variables esperadas del controlador:
 * $carrito  -> Productos que el usuario tiene en su carrito
 * $usuario  -> Datos del usuario si está logueado
 */

$title = "Checkout - The PastryShop";
require_once __DIR__ . '/../layouts/header.php';

if (empty($carrito)): ?>
    <div class="text-center p-5">
        <h4 class="text-muted">Tu carrito está vacío</h4>
        <a href="<?= BASE_URL ?>/productos" class="btn btn-primary">
            Ver productos
        </a>
    </div>
<?php require_once __DIR__ . '/../layouts/footer.php'; return; endif; ?>

<h2 class="mb-4 text-center">Confirmar pedido</h2>

<div class="row">

    <!-- ================================== -->
    <!-- FORMULARIO DE DATOS DEL CLIENTE    -->
    <!-- ================================== -->
    <div class="col-lg-7">

        <div class="card shadow mb-4">
            <div class="card-body">

                <h4 class="mb-3">Datos del cliente</h4>

                <!-- FORMULARIO PRINCIPAL -->
                <form id="formCheckout" method="POST" action="<?= BASE_URL ?>/pedidos/crear">

                    <!-- Nombre -->
                    <div class="mb-3">
                        <label class="form-label">Nombres y apellidos</label>
                        <input type="text" name="nombre"
                               class="form-control"
                               required
                               value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>">
                    </div>

                    <!-- Teléfono -->
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="tel" name="telefono" class="form-control"
                               pattern="\d{9}" placeholder="9 dígitos"
                               required
                               value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>">
                    </div>

                    <!-- Dirección -->
                    <div class="mb-3">
                        <label class="form-label">Dirección de entrega</label>
                        <textarea name="direccion" class="form-control" required><?= htmlspecialchars($usuario['direccion'] ?? '') ?></textarea>
                    </div>

                    <!-- Cupón -->
                    <div class="mb-3">
                        <label class="form-label">Cupón (opcional)</label>

                        <div class="input-group">
                            <input type="text" id="inputCupon" name="cupon" class="form-control"
                                   placeholder="Ej: PASTRY10">
                            <button class="btn btn-dark" type="button" id="btnAplicarCupon">
                                Aplicar
                            </button>
                        </div>

                        <div id="mensajeCupon" class="form-text text-success"></div>
                        <div id="errorCupon" class="form-text text-danger"></div>
                    </div>

                    <hr>

                    <!-- MÉTODOS DE PAGO -->
                    <h4 class="mt-3">Método de pago</h4>

                    <div class="form-check mt-2">
                        <input class="form-check-input" type="radio"
                               name="metodo_pago" value="yape" required>
                        <label class="form-check-label">
                            <img src="<?= BASE_URL ?>/assets/img/yape.png" width="40"> Yape
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio"
                               name="metodo_pago" value="plin">
                        <label class="form-check-label">
                            <img src="<?= BASE_URL ?>/assets/img/plin.png" width="40"> Plin
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio"
                               name="metodo_pago" value="tarjeta">
                        <label class="form-check-label">
                            <i class="bi bi-credit-card-2-front"></i> Tarjeta
                        </label>
                    </div>


                    <!-- TOKEN CSRF -->
                    <input type="hidden" name="csrf_token" value="<?= Security::generateToken() ?>">

                </form>

            </div>
        </div>

    </div>

    <!-- ================================== -->
    <!-- RESUMEN DEL PEDIDO                -->
    <!-- ================================== -->
    <div class="col-lg-5">

        <div class="card shadow">
            <div class="card-body">
                <h4 class="mb-3">Resumen del pedido</h4>

                <?php
                $subtotal = 0;
                foreach ($carrito as $item) {
                    $subtotal += $item['precio'] * $item['cantidad'];
                }
                ?>

                <!-- Lista de productos -->
                <?php foreach ($carrito as $item): ?>
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <?= htmlspecialchars($item['nombre']) ?>  
                            <small class="text-muted">(x<?= $item['cantidad'] ?>)</small>
                        </div>
                        <strong>S/ <?= number_format($item['precio'] * $item['cantidad'], 2) ?></strong>
                    </div>
                <?php endforeach; ?>

                <hr>

                <p class="d-flex justify-content-between">
                    <span>Subtotal:</span>
                    <strong id="subtotal">S/ <?= number_format($subtotal, 2) ?></strong>
                </p>

                <p class="d-flex justify-content-between">
                    <span>Descuento:</span>
                    <strong id="descuento">S/ 0.00</strong>
                </p>

                <hr>

                <p class="d-flex justify-content-between fs-5">
                    <span>Total:</span>
                    <strong id="total">S/ <?= number_format($subtotal, 2) ?></strong>
                </p>

                <!-- Botón para crear pedido -->
                <button class="btn btn-dark w-100 btn-lg mt-3"
                        onclick="document.getElementById('formCheckout').submit();">
                    Confirmar compra <i class="bi bi-bag-check"></i>
                </button>

            </div>
        </div>

    </div>

</div>

<!-- ========================
     SCRIPT CUPÓN (SIMULADO)
   ======================== -->

<script>
document.getElementById("btnAplicarCupon").addEventListener("click", function () {

    const cupon = document.getElementById("inputCupon").value.trim();
    const subtotal = <?= $subtotal ?>;

    // Limpia mensajes
    document.getElementById("mensajeCupon").innerText = "";
    document.getElementById("errorCupon").innerText = "";

    if (cupon === "") {
        document.getElementById("errorCupon").innerText = "Ingresa un cupón válido.";
        return;
    }

    // Simulación — aquí llamarías por AJAX a tu controlador
    if (cupon === "PASTRY10") {
        let desc = subtotal * 0.10;
        document.getElementById("descuento").innerText = "S/ " + desc.toFixed(2);
        document.getElementById("total").innerText = "S/ " + (subtotal - desc).toFixed(2);
        document.getElementById("mensajeCupon").innerText = "Cupón aplicado correctamente.";
    } else {
        document.getElementById("errorCupon").innerText = "Cupón inválido.";
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
