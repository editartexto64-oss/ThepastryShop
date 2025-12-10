<?php
/**
 * HEADER GENERAL DEL SITIO (vista pública)
 * - Carga Bootstrap y CSS global
 * - Barra de navegación
 * - Se usa en todas las vistas públicas
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : "The PastryShop" ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Íconos Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Estilos personalizados -->
    <style>
        body {
            background-color: #fff8fb;
        }
        .navbar {
            background-color: #f5cee0;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 22px;
        }
    </style>
</head>

<body>

<!-- NAVBAR PÚBLICO -->
<nav class="navbar navbar-expand-lg">
    <div class="container">

        <!-- Logo y nombre -->
        <a class="navbar-brand" href="<?= BASE_URL ?>/">
            <i class="bi bi-cake2"></i> The PastryShop
        </a>

        <!-- Botón responsive -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Enlaces -->
        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/productos">Productos</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/carrito">
                        Carrito <i class="bi bi-cart"></i>
                    </a>
                </li>

                <!-- Si está logueado -->
                <?php if (SessionHelper::isLogged()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i>
                            <?= SessionHelper::get('usuario')['nombre'] ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/pedidos/mis-pedidos">Mis pedidos</a></li>

                            <?php if (SessionHelper::isAdmin()): ?>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/admin">Administración</a></li>
                            <?php endif; ?>

                            <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>/auth/logout">
                                <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                            </a></li>
                        </ul>
                    </li>

                <?php else: ?>
                    <li class="nav-item">
                        <a class="btn btn-outline-dark" href="<?= BASE_URL ?>/auth/login">Ingresar</a>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>

<!-- CONTENEDOR PRINCIPAL -->
<div class="container mt-4 mb-4">
