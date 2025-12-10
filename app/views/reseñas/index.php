<?php
/**
 * Vista: reseñas/index.php
 *
 * Variables esperadas:
 * $producto -> Datos del producto (id, nombre, imagen, etc.)
 * $reseñas -> Array con lista de reseñas del producto
 * $usuarioPuedeComentar -> true si el usuario compró el producto
 * $usuarioYaComentó -> true si el usuario ya dejó una reseña
 */

$title = "Reseñas - " . $producto['nombre'];
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container mt-4">

    <!-- ========================================== -->
    <!--    CABECERA DEL PRODUCTO                   -->
    <!-- ========================================== -->
    <div class="d-flex align-items-center mb-4">

        <img src="<?= BASE_URL ?>/uploads/productos/<?= $producto['imagen'] ?>"
             width="120" class="rounded shadow-sm me-3">

        <div>
            <h2 class="fw-bold mb-1"><?= htmlspecialchars($producto['nombre']) ?></h2>
            <p class="text-muted">Reseñas y valoraciones del producto</p>
        </div>

    </div>

    <hr>


    <!-- ========================================== -->
    <!--    FORMULARIO PARA AGREGAR RESEÑA          -->
    <!-- ========================================== -->

    <?php if (!$usuarioPuedeComentar): ?>

        <div class="alert alert-info">
            Solo puedes dejar una reseña si compraste este producto.
        </div>

    <?php elseif ($usuarioYaComentó): ?>

        <div class="alert alert-warning">
            Ya dejaste una reseña para este producto.
        </div>

    <?php else: ?>

        <div class="card shadow mb-4">
            <div class="card-body">

                <h4 class="mb-3">Escribe tu reseña</h4>

                <form method="POST" action="<?= BASE_URL ?>/reseñas/guardar">

                    <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>">

                    <!-- ===================== -->
                    <!-- SISTEMA DE ESTRELLAS  -->
                    <!-- ===================== -->
                    <div class="mb-3">

                        <label class="form-label">Puntuación:</label>

                        <div id="estrellas" class="fs-3 text-warning" style="cursor:pointer;">
                            <!-- estrellas -->
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="bi bi-star estrella" data-valor="<?= $i ?>"></i>
                            <?php endfor; ?>
                        </div>

                        <input type="hidden" name="puntuacion" id="puntuacion" required>

                        <small class="text-muted">Selecciona cuántas estrellas deseas dar.</small>
                    </div>

                    <!-- ===================== -->
                    <!-- COMENTARIO            -->
                    <!-- ===================== -->
                    <div class="mb-3">
                        <label class="form-label">Comentario:</label>
                        <textarea name="comentario" class="form-control" rows="4"
                                  placeholder="Escribe tu reseña..." required></textarea>
                    </div>

                    <button class="btn btn-dark">
                        Publicar reseña
                    </button>
                </form>

            </div>
        </div>

    <?php endif; ?>

    <hr>

    <!-- ========================================== -->
    <!--       LISTA DE RESEÑAS DEL PRODUCTO        -->
    <!-- ========================================== -->

    <h3 class="mb-3">Reseñas de clientes</h3>

    <?php if (empty($reseñas)): ?>

        <div class="alert alert-secondary">
            Este producto aún no tiene reseñas. ¡Sé el primero en comentar!
        </div>

    <?php else: ?>

        <?php foreach ($reseñas as $r): ?>
            <div class="card shadow-sm mb-3">

                <div class="card-body">

                    <!-- NOMBRE DEL USUARIO -->
                    <h5 class="mb-1"><?= htmlspecialchars($r['cliente']) ?></h5>

                    <!-- PUNTUACIÓN -->
                    <div class="text-warning mb-2">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?php if ($i <= $r['puntuacion']): ?>
                                <i class="bi bi-star-fill"></i>
                            <?php else: ?>
                                <i class="bi bi-star"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>

                    <!-- COMENTARIO -->
                    <p><?= nl2br(htmlspecialchars($r['comentario'])) ?></p>

                    <!-- FECHA -->
                    <small class="text-muted"><?= $r['fecha'] ?></small>

                </div>

            </div>
        <?php endforeach; ?>

    <?php endif; ?>

</div>

<!-- ========================================== -->
<!-- SCRIPT PARA SISTEMA DE ESTRELLAS           -->
<!-- ========================================== -->

<script>
    let estrellas = document.querySelectorAll(".estrella");
    estrellas.forEach(e => {
        e.addEventListener("mouseover", function () {
            let valor = this.dataset.valor;
            pintar(valor);
        });

        e.addEventListener("click", function () {
            let valor = this.dataset.valor;
            document.getElementById("puntuacion").value = valor;
        });
    });

    function pintar(valor) {
        estrellas.forEach(e => {
            e.classList.toggle("bi-star-fill", e.dataset.valor <= valor);
            e.classList.toggle("bi-star", e.dataset.valor > valor);
        });
    }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
