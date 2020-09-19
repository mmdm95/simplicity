<?php

namespace App\Logic\Middleware\Logic;

use Sim\Abstracts\Mvc\Controller\Middleware\AbstractMiddleware;

class TestMiddleware extends AbstractMiddleware
{
    public function handle(...$_): bool
    {
        if ($_[0] == 'mmdm') {
            return false;
        }
        return parent::handle(...$_);
    }
}