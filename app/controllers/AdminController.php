<?php
/**
 * AdminController
 * Panel administrativo: gestión de productos, categorías, cupones y pedidos.
 * Todas las acciones están protegidas para usuarios con rol 'admin'.
 */

class AdminController extends Controller
{
    // Middleware simple: verificar rol admin
    private function requireAdmin()
    {
        if (empty($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
            $_SESSION['error'] = "Debe ser administrador para acceder.";
            header("Location: " . BASE_URL . "login");
            exit;
        }
    }

    // Panel principal
    public function index()
    {
        $this->requireAdmin();
        return $this->view('admin/dashboard');
    }

    /* -------------------- PRODUCTOS -------------------- */

    public function productos()
    {
        $this->requireAdmin();
        $productoModel = $this->model('Producto');
        $productos = $productoModel->listarTodos(); // listar incluso inactivos
        return $this->view('admin/productos', ['productos' => $productos]);
    }

    // Crear o editar producto (maneja GET y POST)
    public function productoForm()
    {
        $this->requireAdmin();
        $productoModel = $this->model('Producto');
        $categoriaModel = $this->model('Categoria');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Procesar creación/edición
            try {
                $data = [
                    'categoria_id' => intval($_POST['categoria_id']),
                    'nombre' => trim($_POST['nombre']),
                    'descripcion' => trim($_POST['descripcion']),
                    'precio' => floatval($_POST['precio']),
                    'imagen' => $_POST['imagen'] ?? null,
                    'stock' => intval($_POST['stock']),
                    'activo' => isset($_POST['activo']) ? 1 : 0
                ];

                if (!empty($_POST['id'])) {
                    // editar
                    $productoModel->editar($_POST['id'], $data);
                    $_SESSION['success'] = "Producto actualizado.";
                } else {
                    // crear
                    $productoModel->crear($data);
                    $_SESSION['success'] = "Producto creado.";
                }
                header("Location: " . 'BASE_URL' . "admin/productos");
                return;
            } catch (Exception $e) {
                error_log("Admin productoForm error: " . $e->getMessage());
                $_SESSION['error'] = "Error guardando producto.";
                header("Location: " . 'BASE_URL' . "admin/productos");
                return;
            }
        }

        // GET -> mostrar formulario
        $categorias = $categoriaModel->listarActivas();
        $producto = null;
        if (!empty($_GET['id'])) {
            $producto = $productoModel->obtener(intval($_GET['id']));
        }

        return $this->view('admin/producto_form', [
            'categorias' => $categorias,
            'producto' => $producto
        ]);
    }

    public function productoToggleActivo()
    {
        $this->requireAdmin();
        $id = intval($_POST['id'] ?? 0);
        try {
            $productoModel = $this->model('Producto');
            $producto = $productoModel->obtener($id);
            if (!$producto) throw new Exception("Producto no encontrado");
            $nuevo = $producto['activo'] ? 0 : 1;
            $productoModel->setActivo($id, $nuevo);
            $_SESSION['success'] = "Estado del producto actualizado.";
        } catch (Exception $e) {
            $_SESSION['error'] = "No se pudo cambiar el estado.";
        }
        header("Location: " . 'BASE_URL' . "admin/productos");
    }

    /* -------------------- CATEGORÍAS -------------------- */

    public function categorias()
    {
        $this->requireAdmin();
        $categoriaModel = $this->model('Categoria');
        $categorias = $categoriaModel->listarTodas();
        return $this->view('admin/categorias', ['categorias' => $categorias]);
    }

    public function categoriaGuardar()
    {
        $this->requireAdmin();
        $categoriaModel = $this->model('Categoria');
        $nombre = trim($_POST['nombre'] ?? '');
        if (!$nombre) {
            $_SESSION['error'] = "Nombre requerido";
            header("Location: " . BASE_URL . "admin/categorias");
            return;
        }

        if (!empty($_POST['id'])) {
            $categoriaModel->editar($_POST['id'], $nombre);
            $_SESSION['success'] = "Categoría actualizada.";
        } else {
            $categoriaModel->crear($nombre);
            $_SESSION['success'] = "Categoría creada.";
        }

        header("Location: " . BASE_URL . "admin/categorias");
    }

    /* -------------------- CUPONES -------------------- */

    public function cupones()
    {
        $this->requireAdmin();
        $cuponModel = $this->model('Cupon');
        $cupones = $cuponModel->listarTodos();
        return $this->view('admin/cupones', ['cupones' => $cupones]);
    }

    public function cuponGuardar()
    {
        $this->requireAdmin();
        $cuponModel = $this->model('Cupon');

        $data = [
            'codigo' => trim($_POST['codigo'] ?? ''),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'descuento_porcentaje' => intval($_POST['descuento'] ?? 0),
            'fecha_inicio' => $_POST['fecha_inicio'] ?? null,
            'fecha_fin' => $_POST['fecha_fin'] ?? null,
            'uso_maximo' => intval($_POST['uso_maximo'] ?? 0),
            'activo' => isset($_POST['activo']) ? 1 : 0
        ];

        if (!empty($_POST['id'])) {
            $cuponModel->editar($_POST['id'], $data);
            $_SESSION['success'] = "Cupón actualizado.";
        } else {
            $cuponModel->crear($data);
            $_SESSION['success'] = "Cupón creado.";
        }

        header("Location: " . BASE_URL . "admin/cupones");
    }

    /* -------------------- PEDIDOS -------------------- */

    public function pedidos()
    {
        $this->requireAdmin();
        $pedidoModel = $this->model('Pedido');
        $pedidos = $pedidoModel->listarTodos();
        return $this->view('admin/pedidos', ['pedidos' => $pedidos]);
    }

    public function pedidoVer()
    {
        $this->requireAdmin();
        $id = intval($_GET['id'] ?? 0);
        $pedidoModel = $this->model('Pedido');
        $detalleModel = $this->model('DetallePedido');

        $pedido = $pedidoModel->obtener($id);
        $detalles = $detalleModel->obtenerPorPedido($id);

        return $this->view('admin/pedido_ver', [
            'pedido' => $pedido,
            'detalles' => $detalles
        ]);
    }
}
