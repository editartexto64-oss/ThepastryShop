<?php
/**
 * ResenaController
 * Gestión de reseñas/valoraciones de productos por clientes.
 */

class ResenaController extends Controller
{
    // Publicar reseña (POST)
    public function publicar()
    {
        // Verificar usuario autenticado
        if (empty($_SESSION['usuario'])) {
            echo json_encode(['ok' => false, 'msg' => 'Debe iniciar sesión para publicar una reseña.']);
            return;
        }

        $usuarioId = $_SESSION['usuario']['id'] ?? null;
        $productoId = intval($_POST['producto_id'] ?? 0);
        $calificacion = intval($_POST['calificacion'] ?? 0);
        $comentario = trim($_POST['comentario'] ?? '');

        if ($productoId <= 0 || $calificacion < 1 || $calificacion > 5) {
            echo json_encode(['ok' => false, 'msg' => 'Datos invalidos para la reseña.']);
            return;
        }

        try {
            $resenaModel = $this->model('Resena');
            $data = [
                'usuario_id' => $usuarioId,
                'producto_id' => $productoId,
                'calificacion' => $calificacion,
                'comentario' => $comentario
            ];
            $ok = $resenaModel->crear($data);

            if (!$ok) throw new Exception("No se pudo guardar la reseña.");

            echo json_encode(['ok' => true, 'msg' => 'Reseña publicada.']);
            return;

        } catch (Exception $e) {
            error_log("ResenaController::publicar - " . $e->getMessage());
            echo json_encode(['ok' => false, 'msg' => 'Error al publicar reseña.']);
            return;
        }
    }

    // Listar reseñas de un producto (GET)
    public function listar()
    {
        $productoId = intval($_GET['producto_id'] ?? 0);
        if ($productoId <= 0) {
            echo json_encode(['ok' => false, 'msg' => 'Producto no especificado.']);
            return;
        }

        try {
            $resenaModel = $this->model('Resena');
            $lista = $resenaModel->listarPorProducto($productoId);
            echo json_encode(['ok' => true, 'data' => $lista]);
            return;
        } catch (Exception $e) {
            error_log("ResenaController::listar - " . $e->getMessage());
            echo json_encode(['ok' => false, 'msg' => 'Error al listar reseñas.']);
            return;
        }
    }

    // Eliminar reseña (solo admin o autor)
    public function eliminar()
    {
        $this->requireAuthForResenaAction();
        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['ok' => false, 'msg' => 'ID inválido.']);
            return;
        }

        try {
            $resenaModel = $this->model('Resena');
            // Se asume método eliminar en el modelo
            $ok = $resenaModel->eliminar($id);
            echo json_encode(['ok' => $ok, 'msg' => $ok ? 'Reseña eliminada.' : 'No se pudo eliminar.']);
            return;
        } catch (Exception $e) {
            error_log("ResenaController::eliminar - " . $e->getMessage());
            echo json_encode(['ok' => false, 'msg' => 'Error eliminando reseña.']);
            return;
        }
    }

    private function requireAuthForResenaAction()
    {
        if (empty($_SESSION['usuario'])) {
            echo json_encode(['ok' => false, 'msg' => 'Acceso denegado.']);
            exit;
        }
    }
}
