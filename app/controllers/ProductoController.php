<?php

class ProductoController extends Controller
{
    // Lista de productos del catálogo
    public function index()
    {
        $productoModel = $this->model("Producto");
        $productos = $productoModel->listarActivos();

        return $this->view("productos/listado", [
            'productos' => $productos
        ]);
    }

    // Detalle de un producto
    public function show()
    {
        if (!isset($_GET['id'])) {
            die("ID de producto no proporcionado.");
        }

        $id = intval($_GET['id']);
        $productoModel = $this->model("Producto");
        $producto = $productoModel->obtener($id);

        if (!$producto) {
            die("Producto no encontrado.");
        }

        return $this->view("productos/detalle", [
            'producto' => $producto
        ]);
    }
}
