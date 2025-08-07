<?php

namespace App\Controllers;

use App\Helper\View;

class LandingController
{
    public function index()
    {
        View::render('landing/index.php');
    }
}
