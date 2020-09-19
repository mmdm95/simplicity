<?php

namespace Sim\Database\Builder;


interface IWhere
{
    /**
     * @param $where
     * @param string|null $operation
     * @param string|null $other
     * @return mixed
     */
    public function where($where, $operation = null, string $other = null);

    /**
     * @param $where
     * @param string|null $operation
     * @param string|null $other
     * @return mixed
     */
    public function orWhere($where, $operation = null, string $other = null);
}