<?php

namespace App\Helper;

class View
{
    public static function render(string $viewPath, array $data = []): void
    {
        $view = BASE_PATH . "/views/$viewPath";
        $layout = BASE_PATH . '/views/layout.php';

        if (!file_exists($view)) {
            die("View não encontrada: " . $view);
        }

        // Extrai variáveis para dentro do escopo da view
        extract($data, EXTR_SKIP);

        require $layout;
    }
}
