<?php
/**
 * LAYOUT DEL PANEL DE ADMINISTRACIÓN
 * - Sidebar lateral
 * - Bootstrap
 * - Diseño tipo Dashboard
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin | The PastryShop</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { background-color: #faf1f6; }
        .sidebar {
            width: 240px;
            height: 100vh;
            background: #f3b8d1;
            position: fixed;
            padding-top: 20px;
        }
        .sidebar a {
            color: #000;
            padding: 12px;
            display: block;
            font-weight: 500;
        }
        .sidebar a:hover {
            background: #ffffff60;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
        }
    </style>
</head>

<body>

<!-- BARRA LATERAL -->
<div class="sidebar">

    <h4 class="text-center">
        <i class="bi bi-speedometer"></i> Admin
    </h4>

    <a href="<?= BASE_URL ?>/admin"><i class="bi bi-house-door"></i> Inicio</a>
    <a href="<?= BASE_URL ?>/productos/admin"><i class="bi bi-cake2"></i> Productos</a>
    <a href="<?= BASE_URL ?>/categorias/admin"><i class="bi bi-tags"></i> Categorías</a>
    <a href="<?= BASE_URL ?>/pedidos/admin"><i class="bi bi-bag-check"></i> Pedidos</a>
    <a href="<?= BASE_URL ?>/cupones/admin"><i class="bi bi-ticket"></i> Cupones</a>
    <a href="<?= BASE_URL ?>/auth/logout" class="text-danger"><i class="bi bi-box-arrow-right"></i> Salir</a>

</div>

<!-- CONTENIDO -->
<div class="content">
