<?php

namespace App\Logic;

use Sim\Interfaces\IInitialize;

class Helper implements IInitialize
{
    /**
     * Return an array of helpers that you need to load
     */
    public function init()
    {
        // helper path string that is
        // usually in logic helper folder
        // and prefixed by
        // __DIR__ . '/Helpers/'
        return [

        ];
    }
}