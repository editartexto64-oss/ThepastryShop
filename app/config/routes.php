<?php

return [

    '' => 'HomeController@index',

    'login' => 'AuthController@login',
    'register' => 'AuthController@register',
    'logout' => 'AuthController@logout',

    'productos' => 'ProductoController@index',
    'producto/ver' => 'ProductoController@show',

    'carrito' => 'CarritoController@index',

    'checkout' => 'PedidoController@checkout',
    'confirmar' => 'PedidoController@confirmar',

    'tracking' => 'TrackingController@index',

    // Rutas administrador
    'admin/productos' => 'AdminController@productos',
    'admin/categorias' => 'AdminController@categorias',
    'admin/pedidos' => 'AdminController@pedidos',
    'admin/cupones' => 'AdminController@cupones'
];
