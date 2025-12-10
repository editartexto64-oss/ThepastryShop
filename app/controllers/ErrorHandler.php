<?php

class ErrorHandler extends Controller
{
    public static function mostrar404()
    {
        http_response_code(404);
        require VIEW_PATH . "errores/404.php";
        exit;
    }

    public static function mostrar500()
    {
        http_response_code(500);
        require VIEW_PATH . "errores/500.php";
        exit;
    }

    public static function mostrar($codigo = 500, $mensaje = "Error interno")
    {
        http_response_code($codigo);
        
        $data = [
            "codigo" => $codigo,
            "mensaje" => $mensaje
        ];

        require VIEW_PATH . "errores/500.php";
        exit;
    }
}


