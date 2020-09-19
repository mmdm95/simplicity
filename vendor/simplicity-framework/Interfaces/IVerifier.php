<?php

namespace Sim\Interfaces;


interface IVerifier
{
    /**
     * @param $input
     * @return mixed
     */
    public function verify($input);
}