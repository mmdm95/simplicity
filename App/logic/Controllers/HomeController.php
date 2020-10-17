<?php

namespace App\Logic\Controllers;

use App\Logic\Models\Model;
use Sim\Abstracts\Mvc\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index()
    {
        $this->isIndividual(true)
            ->setTemplate('partial/simple');

        return $this->render();
    }

    public function show($id)
    {
        return $id;
    }
}