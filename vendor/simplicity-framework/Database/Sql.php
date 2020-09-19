<?php

namespace Sim\Database;


use Sim\Database\Builder\IDelete;
use Sim\Database\Builder\IInsert;
use Sim\Database\Builder\ISelect;
use Sim\Database\Builder\IUpdate;

class Sql
{
    const MYSQL = 'Mysql';
    const POSGRE = 'Posgre';
    const SQLITE = 'Sqlite';
    const SQLSRV = 'Sqlsrv';

    /**
     * @var string $db
     */
    protected $db;

    /**
     * @var string $namespace
     */
    protected $namespace;

    /**
     * Sql constructor.
     * @param string $db
     */
    public function __construct(string $db)
    {
        $this->db = ucfirst(strtolower($db));
        $this->namespace = 'Sim\\Database\\Drivers\\' . $this->db;
    }

    /**
     * @return ISelect
     */
    public function select()
    {
        return $this->instance('Select');
    }

    /**
     * @return IInsert
     */
    public function insert()
    {
        return $this->instance('Insert');
    }

    /**
     * @return IUpdate
     */
    public function update()
    {
        return $this->instance('Update');
    }

    /**
     * @return IDelete
     */
    public function delete()
    {
        return $this->instance('Delete');
    }

    /**
     * @param $klass
     * @return mixed
     */
    protected function instance($klass)
    {
        $class = $this->namespace . '\\' . $klass;
        return new $class($this->db);
    }
}