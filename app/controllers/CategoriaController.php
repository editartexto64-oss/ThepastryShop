<?php
/**
 * CategoriaController
 *
 * Controlador responsable de gestionar las categorías (público y admin).
 * - Métodos públicos: listar (para frontend), ver (opcional)
 * - Métodos admin: index (listado), crear, editar, eliminar, toggleActivo
 *
 * Requiere:
 * - Modelo: Categoria (implementar los métodos listAll, listActive, create, update, delete, getById, setActivo)
 * - Helpers: SessionHelper, Security, Upload
 *
 * Buenas prácticas:
 * - Todas las entradas provenientes de $_POST/$_GET pasan por sanitización
 * - Uso de try/catch para manejo de errores y logging
 * - Redirecciones y mensajes flash (SessionHelper)
 */

class CategoriaController extends Controller
{
    private $categoriaModel;
    private $uploadDir = "uploads/categorias/"; // carpeta relativa desde public/

    public function __construct()
    {
        // Instanciar el modelo de categoría
        $this->categoriaModel = $this->model('Categoria'); // método heredado del Controller
    }

    /* ============================
     * FRONTEND: listado de categorías (público)
     * Ruta posible: GET /categorias
     * Retorna: vista con categorías activas
     * ============================ */
    public function listar()
    {
        try {
            // Obtener solo categorías activas para mostrar en el frontend
            $categorias = $this->categoriaModel->listarActivas();

            // Renderizar vista pública (ejemplo: parte del home o sidebar)
            return $this->view('categorias/listado', [
                'categorias' => $categorias
            ]);
        } catch (Exception $e) {
            error_log("CategoriaController::listar - " . $e->getMessage());
            // En caso de error, mostrar 500 o redirigir
            ErrorHandler::mostrar500();
        }
    }

    /* ============================
     * ADMIN: panel principal de categorías
     * Ruta: GET /categorias/admin
     * ============================ */
    public function adminIndex()
    {
        // Verificar que sea admin (asumimos SessionHelper)
        if (!SessionHelper::isAdmin()) {
            SessionHelper::set('error', 'Acceso denegado.');
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        try {
            // Obtener todas las categorías (incluye inactivas)
            $categorias = $this->categoriaModel->listarTodas();

            return $this->view('categorias/admin_crud', [
                'categorias' => $categorias
            ]);
        } catch (Exception $e) {
            error_log("CategoriaController::adminIndex - " . $e->getMessage());
            ErrorHandler::mostrar500();
        }
    }

    /* ============================
     * ADMIN: crear categoría
     * Ruta: POST /categorias/crear
     * ============================ */
    public function crear()
    {
        // Solo admin puede crear
        if (!SessionHelper::isAdmin()) {
            SessionHelper::set('error', 'Acceso denegado.');
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        try {
            // Verificar método POST
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: ' . BASE_URL . '/categorias/admin');
                exit;
            }

            // Validar CSRF si aplica
            $csrf = $_POST['csrf_token'] ?? null;
            if ($csrf && !Security::validateToken($csrf)) {
                SessionHelper::set('error', 'Token inválido.');
                header('Location: ' . BASE_URL . '/categorias/admin');
                exit;
            }

            // Recibir y sanitizar campos
            $nombre = trim($_POST['nombre'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $activo = isset($_POST['activo']) ? (int)$_POST['activo'] : 0;

            if ($nombre === '') {
                SessionHelper::set('error', 'El nombre es obligatorio.');
                header('Location: ' . BASE_URL . '/categorias/admin');
                exit;
            }

            // Manejo de imagen (opcional)
            $imagenNombre = null;
            if (!empty($_FILES['imagen']['name'])) {
                $upload = new Upload( ROOT_PATH . '/public/' . $this->uploadDir );
                $res = $upload->subirImagen($_FILES['imagen']);
                if ($res === false) {
                    SessionHelper::set('error', 'Error subiendo la imagen.');
                    header('Location: ' . BASE_URL . '/categorias/admin');
                    exit;
                }
                $imagenNombre = $res;
            }

            // Preparar datos
            $data = [
                'nombre' => Security::clean($nombre),
                'descripcion' => Security::clean($descripcion),
                'imagen' => $imagenNombre,
                'activo' => $activo
            ];

            // Llamar al modelo
            $ok = $this->categoriaModel->crear($data);

            if ($ok) {
                SessionHelper::set('success', 'Categoría creada correctamente.');
            } else {
                SessionHelper::set('error', 'No se pudo crear la categoría.');
            }

            header('Location: ' . BASE_URL . '/categorias/admin');
            exit;

        } catch (Exception $e) {
            error_log("CategoriaController::crear - " . $e->getMessage());
            SessionHelper::set('error', 'Ocurrió un error creando la categoría.');
            header('Location: ' . BASE_URL . '/categorias/admin');
            exit;
        }
    }

    /* ============================
     * ADMIN: editar categoría
     * Ruta: POST /categorias/editar
     * ============================ */
    public function editar()
    {
        if (!SessionHelper::isAdmin()) {
            SessionHelper::set('error', 'Acceso denegado.');
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: ' . BASE_URL . '/categorias/admin');
                exit;
            }

            $id = (int)($_POST['id_categoria'] ?? 0);
            if ($id <= 0) {
                SessionHelper::set('error', 'ID inválido.');
                header('Location: ' . BASE_URL . '/categorias/admin');
                exit;
            }

            // CSRF
            $csrf = $_POST['csrf_token'] ?? null;
            if ($csrf && !Security::validateToken($csrf)) {
                SessionHelper::set('error', 'Token inválido.');
                header('Location: ' . BASE_URL . '/categorias/admin');
                exit;
            }

            // Campos
            $nombre = trim($_POST['nombre'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $activo = isset($_POST['activo']) ? (int)$_POST['activo'] : 0;

            if ($nombre === '') {
                SessionHelper::set('error', 'El nombre es obligatorio.');
                header('Location: ' . BASE_URL . '/categorias/admin');
                exit;
            }

            // Obtener categoría actual (para saber imagen previa)
            $categoriaActual = $this->categoriaModel->obtener($id);
            if (!$categoriaActual) {
                SessionHelper::set('error', 'Categoría no encontrada.');
                header('Location: ' . BASE_URL . '/categorias/admin');
                exit;
            }

            // Manejo de imagen (opcional: si suben nueva imagen, reemplaza)
            $imagenNombre = $categoriaActual['imagen'] ?? null;
            if (!empty($_FILES['imagen']['name'])) {
                $upload = new Upload( ROOT_PATH . '/public/' . $this->uploadDir );
                $res = $upload->subirImagen($_FILES['imagen']);
                if ($res === false) {
                    SessionHelper::set('error', 'Error subiendo la imagen.');
                    header('Location: ' . BASE_URL . '/categorias/admin');
                    exit;
                }
                // borrar la imagen anterior si existe (opcional)
                if (!empty($imagenNombre) && file_exists(ROOT_PATH . '/public/' . $this->uploadDir . $imagenNombre)) {
                    @unlink(ROOT_PATH . '/public/' . $this->uploadDir . $imagenNombre);
                }
                $imagenNombre = $res;
            }

            // Preparar datos para actualizar
            $data = [
                'nombre' => Security::clean($nombre),
                'descripcion' => Security::clean($descripcion),
                'imagen' => $imagenNombre,
                'activo' => $activo
            ];

            $ok = $this->categoriaModel->editar($id, $data);

            if ($ok) {
                SessionHelper::set('success', 'Categoría actualizada.');
            } else {
                SessionHelper::set('error', 'No se pudo actualizar la categoría.');
            }

            header('Location: ' . BASE_URL . '/categorias/admin');
            exit;

        } catch (Exception $e) {
            error_log("CategoriaController::editar - " . $e->getMessage());
            SessionHelper::set('error', 'Ocurrió un error editando la categoría.');
            header('Location: ' . BASE_URL . '/categorias/admin');
            exit;
        }
    }

    /* ============================
     * ADMIN: eliminar categoría
     * Ruta: POST /categorias/eliminar
     * ============================ */
    public function eliminar()
    {
        if (!SessionHelper::isAdmin()) {
            SessionHelper::set('error', 'Acceso denegado.');
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: ' . BASE_URL . '/categorias/admin');
                exit;
            }

            $id = (int)($_POST['id_categoria'] ?? 0);
            if ($id <= 0) {
                SessionHelper::set('error', 'ID inválido.');
                header('Location: ' . BASE_URL . '/categorias/admin');
                exit;
            }

            // Obtener categoría para borrar imagen si aplica
            $categoria = $this->categoriaModel->obtener($id);
            if (!$categoria) {
                SessionHelper::set('error', 'Categoría no encontrada.');
                header('Location: ' . BASE_URL . '/categorias/admin');
                exit;
            }

            // Intentar eliminar (modelo puede soft-delete o hard-delete)
            $ok = $this->categoriaModel->eliminar($id);

            if ($ok) {
                // Borrar imagen física si existe
                if (!empty($categoria['imagen']) && file_exists(ROOT_PATH . '/public/' . $this->uploadDir . $categoria['imagen'])) {
                    @unlink(ROOT_PATH . '/public/' . $this->uploadDir . $categoria['imagen']);
                }
                SessionHelper::set('success', 'Categoría eliminada.');
            } else {
                SessionHelper::set('error', 'No se pudo eliminar la categoría. Verifique dependencias.');
            }

            header('Location: ' . BASE_URL . '/categorias/admin');
            exit;

        } catch (Exception $e) {
            error_log("CategoriaController::eliminar - " . $e->getMessage());
            SessionHelper::set('error', 'Ocurrió un error eliminando la categoría.');
            header('Location: ' . BASE_URL . '/categorias/admin');
            exit;
        }
    }

    /* ============================
     * ADMIN: activar/inactivar categoría (toggle)
     * Ruta: POST /categorias/toggle
     * ============================ */
    public function toggleActivo()
    {
        if (!SessionHelper::isAdmin()) {
            SessionHelper::set('error', 'Acceso denegado.');
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: ' . BASE_URL . '/categorias/admin');
                exit;
            }

            $id = (int)($_POST['id_categoria'] ?? 0);
            $nuevo = isset($_POST['activo']) ? (int)$_POST['activo'] : 0;

            if ($id <= 0) {
                SessionHelper::set('error', 'ID inválido.');
                header('Location: ' . BASE_URL . '/categorias/admin');
                exit;
            }

            $ok = $this->categoriaModel->setActivo($id, $nuevo);

            if ($ok) {
                SessionHelper::set('success', 'Estado actualizado.');
            } else {
                SessionHelper::set('error', 'No se pudo actualizar el estado.');
            }

            header('Location: ' . BASE_URL . '/categorias/admin');
            exit;

        } catch (Exception $e) {
            error_log("CategoriaController::toggleActivo - " . $e->getMessage());
            SessionHelper::set('error', 'Ocurrió un error actualizando el estado.');
            header('Location: ' . BASE_URL . '/categorias/admin');
            exit;
        }
    }

    /* ============================
     * API SIMPLE: devolver JSON con categorías activas
     * Ruta: GET /api/categorias/activos
     * ============================ */
    public function apiActivas()
    {
        try {
            $categorias = $this->categoriaModel->listarActivas();
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['ok' => true, 'data' => $categorias]);
            exit;
        } catch (Exception $e) {
            error_log("CategoriaController::apiActivas - " . $e->getMessage());
            header('Content-Type: application/json; charset=utf-8', true, 500);
            echo json_encode(['ok' => false, 'msg' => 'Error recuperando categorías']);
            exit;
        }
    }
}
