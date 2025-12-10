<?php

spl_autoload_register(function ($class) {

    $paths = [
        '../app/controllers/' . $class . '.php',
        '../app/models/' . $class . '.php',
        '../app/core/' . $class . '.php',
        '../app/helpers/' . $class . '.php',
    ];

    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
