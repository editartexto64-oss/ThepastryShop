<?php

class CarritoController extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
    }

    public function index()
    {
        return $this->view("carrito/index", [
            'carrito' => $_SESSION['carrito']
        ]);
    }

    // Agregar producto al carrito
    public function agregar()
    {
        $id = intval($_POST['producto_id']);
        $cantidad = intval($_POST['cantidad']);

        $productoModel = $this->model("Producto");
        $producto = $productoModel->obtener($id);

        if (!$producto) {
            die("Producto no válido.");
        }

        // Si ya existe, aumentar cantidad
        if (isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id]['cantidad'] += $cantidad;
        } else {
            // Registrar nuevo producto en carrito
            $_SESSION['carrito'][$id] = [
                'id' => $producto['id'],
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'cantidad' => $cantidad,
                'imagen' => $producto['imagen']
            ];
        }

        header("Location: " . BASE_URL . "carrito");
    }

    // Eliminar un producto del carrito
    public function eliminar()
    {
        $id = $_GET['id'] ?? null;

        if ($id && isset($_SESSION['carrito'][$id])) {
            unset($_SESSION['carrito'][$id]);
        }

        header("Location: " . 'BASE_URL' . "carrito");
    }

    // Actualizar cantidades desde la vista
    public function actualizar()
    {
        foreach ($_POST['cantidad'] as $id => $cantidad) {
            if ($cantidad <= 0) {
                unset($_SESSION['carrito'][$id]);
            } else {
                $_SESSION['carrito'][$id]['cantidad'] = intval($cantidad);
            }
        }

        header("Location: " . BASE_URL . "carrito");
    }
}
