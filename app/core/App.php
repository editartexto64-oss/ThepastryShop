<?php

class App
{
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];
    protected $routes;

    public function __construct()
    {
        $this->routes = require '../app/config/routes.php';
        $this->parseUrl();
        $this->dispatch();
    }

    private function parseUrl()
    {
        $url = $_GET['url'] ?? '';
        $url = trim($url, '/');

        if (array_key_exists($url, $this->routes)) {
            list($this->controller, $this->method) = explode('@', $this->routes[$url]);
        }
    }

    private function dispatch()
    {
        if (!file_exists("../app/controllers/{$this->controller}.php")) {
            die("Controlador no encontrado: {$this->controller}");
        }

        $controller = new $this->controller;

        if (!method_exists($controller, $this->method)) {
            die("Método no encontrado: {$this->method}");
        }

        call_user_func_array([$controller, $this->method], $this->params);
    }
}
