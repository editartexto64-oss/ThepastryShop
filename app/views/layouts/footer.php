<?php
/**
 * FOOTER GENERAL DEL SITIO
 * - Cierran contenedor y body
 * - Scripts JS necesarios
 * - Bootstrap, Popper, módulos utilitarios
 */
?>

</div> <!-- cierre container -->

<footer class="text-center py-4 mt-5" style="background:#fce3ee;">
    <p class="mb-0">© <?= date('Y') ?> The PastryShop — Todos los derechos reservados.</p>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script general de alertas -->
<script>
/**
 * Elimina automáticamente alertas Flash luego de 3s
 */
document.addEventListener("DOMContentLoaded", () => {
    const alert = document.querySelector(".alert-auto");
    if (alert) {
        setTimeout(() => { alert.remove(); }, 3000);
    }
});
</script>

</body>
</html>
