<?php

class View
{
    public static function render($view, $data = [])
    {
        extract($data);
        require "../views/{$view}.php";
    }

    public static function renderWithLayout($view, $data = [], $layout = "layouts/main")
    {
        extract($data);

        require "../views/{$layout}_header.php";
        require "../views/{$view}.php";
        require "../views/{$layout}_footer.php";
    }
}
