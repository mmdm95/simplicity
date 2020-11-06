<?php

namespace App\Logic\Controllers;

use Sim\Abstracts\Mvc\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index()
    {
        $this->setTemplate('partial/simple');

        return $this->render();
    }

    public function show($id)
    {
        return $id;
    }
}