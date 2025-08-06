<?php

namespace App\Controllers;

class LandingController
{
    public function index()
    {
        $view = BASE_PATH . '/views/landing/index.php';
        $layout = BASE_PATH . '/views/layout.php';

        if (file_exists($view)) {
            require $layout;
        } else {
            die("View não encontrada: " . $view);
        }
    }
}
