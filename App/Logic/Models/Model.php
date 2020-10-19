<?php

namespace App\Logic\Models;

class Model extends BaseModel
{
    public function test()
    {
        $select = $this->connector->select();

        $select->cols(['*'])->from('faq');

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }
}