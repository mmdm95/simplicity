<?php

namespace App\Logic\Controller;

use Sim\Abstracts\Mvc\Controller\AbstractController;

class PageController extends AbstractController
{
    public function notFound()
    {
        return $this->show404();
    }

    public function adminNotFound()
    {
        return $this->show404();
    }

    public function serverError()
    {
        return $this->show500();
    }
}