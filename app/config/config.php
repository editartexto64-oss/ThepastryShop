<?php

// Ruta absoluta al proyecto
define('ROOT_PATH', realpath(__DIR__ . '/../..') . DIRECTORY_SEPARATOR);

// Ruta a la carpeta /app/
define('APP_PATH', ROOT_PATH . 'app' . DIRECTORY_SEPARATOR);

// Ruta a la carpeta /app/views/
define('VIEW_PATH', APP_PATH . 'views' . DIRECTORY_SEPARATOR);

// URL base del proyecto
define('BASE_URL', "http://localhost/thepastryshop/public/");

// Opciones generales
return [
    'app_name' => 'ThePastryShop',
    'timezone' => 'America/Lima',
];
