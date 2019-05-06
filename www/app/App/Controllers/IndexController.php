<?php

namespace App\Controllers;

use One\Http\Controller;

class IndexController extends Controller
{

    public function index()
    {
        return 'hello world';
    }

    public function data(...$args)
    {
        return $this->json($args);
    }
}




