<?php

namespace App\Logic\Controller;

use App\Logic\Model\Model;
use Sim\Abstracts\Mvc\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index()
    {
        $t = new Model();

        $this->isIndividual(true)
            ->setTemplate('partial/simple');

        return $this->render();
    }

    public function show($id)
    {
        return $id;
    }
}