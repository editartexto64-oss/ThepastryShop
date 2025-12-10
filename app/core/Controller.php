<?php

class Controller
{
    /**
     * Cargar modelo
     */
    public function model($model)
    {
        require_once APP_PATH . "/models/{$model}.php";
        return new $model();
    }

    /**
     * Cargar vista
     */
    public function view($archivo, $data = [])
    {
        $viewFile = VIEW_PATH . $archivo . ".php";

        if (!file_exists($viewFile)) {
            require_once APP_PATH . "/controllers/ErrorHandler.php";
            ErrorHandler::mostrar404();
            exit;
        }

        extract($data);
        require_once $viewFile;
    }

    /**
     * Redirección segura
     */
    public function redirect($ruta)
    {
        $url = rtrim(BASE_URL, "/") . "/" . ltrim($ruta, "/");
        header("Location: " . $url);
        exit;
    }
}
