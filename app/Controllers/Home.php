<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Home extends BaseController
{
    public function getIndex(): string
    {
        return view('welcome_message', ['titulo' => 'Home']);
    }

    public function getInicio(){
        return view('welcome_message', ['titulo' => 'Home']);
    }
}
