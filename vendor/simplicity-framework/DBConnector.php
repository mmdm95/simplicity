<?php

namespace Sim;

use Aura\Sql\ConnectionLocator;
use Aura\Sql\ExtendedPdo;
use Aura\Sql\ExtendedPdoInterface;
use Aura\SqlQuery\Common\Delete;
use Aura\SqlQuery\Common\DeleteInterface;
use Aura\SqlQuery\Common\Insert;
use Aura\SqlQuery\Common\InsertInterface;
use Aura\SqlQuery\Common\Select;
use Aura\SqlQuery\Common\SelectInterface;
use Aura\SqlQuery\Common\UpdateInterface;
use Aura\SqlQuery\QueryFactory;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class DBConnector
{
    /**
     * @var string
     */
    protected $table;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    private $types = array();

    /**
     * @var ConnectionLocator|null
     */
    private $locator = null;

    /**
     * @var QueryFactory|null
     */
    private $factory = null;

    /**
     * @var ExtendedPdoInterface
     */
    protected $db = null;

    /**
     * BaseModel constructor.
     * @throws ConfigNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function __construct()
    {
        $this->db = $this->getDb();
    }

    /**
     * @return SelectInterface|Select|null
     */
    public function select()
    {
        if (isset($this->factory)) {
            return $this->factory->newSelect();
        }
        return null;
    }

    /**
     * @return InsertInterface|Insert|null
     */
    public function insert()
    {
        if (isset($this->factory)) {
            return $this->factory->newInsert();
        }
        return null;
    }

    /**
     * @return UpdateInterface|null
     */
    public function update()
    {
        if (isset($this->factory)) {
            return $this->factory->newUpdate();
        }
        return null;
    }

    /**
     * @return DeleteInterface|Delete|null
     */
    public function delete()
    {
        if (isset($this->factory)) {
            return $this->factory->newDelete();
        }
        return null;
    }

    /**
     * Return PDO instance
     *
     * @return \PDO
     */
    public function getPDO(): \PDO
    {
        return $this->db->getPdo();
    }

    /**
     * @param string $name
     * @param string $type
     * @return ExtendedPdoInterface
     * @throws ConfigNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function getDb($name = 'default', $type = 'read')
    {
        if (isset($this->db)) {
            return $this->db;
        }
        $this->setDb($name, $type);
        return $this->db;
    }

    /**
     * @param string $name
     * @param string $type
     * @return static
     * @throws ConfigNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    private function setDb($name = 'default', $type = 'read')
    {
        if (!isset($this->locator)) {
            $this->setConnectionLocator();
        }

        if (strtolower($name) != 'default' && strtolower($type) == 'read') {
            $this->db = $this->locator->getRead($name);
            $this->type = $this->types[$type][$name];
        } else if (strtolower($name) != 'default' && strtolower($type) == 'write') {
            $this->db = $this->locator->getWrite($name);
            $this->type = $this->types[$type][$name];
        } else {
            $this->db = $this->locator->getDefault();
            $this->type = $this->types[$name];
        }

        if (!$this->db->isConnected()) {
            $this->db->connect();
        }

        $this->factory = new QueryFactory($this->type);

        return $this;
    }

    /**
     * Parse database config file
     *
     * @throws ConfigNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    private function setConnectionLocator()
    {
        $dbs = config()->get('database.databases');
        $default = null;
        $read = [];
        $write = [];
        $types = [];

        foreach ($dbs as $k => $v) {
            if (strtolower($k) == 'default') {
                $types['default'] = $v['type'];
                $dsn = $v['dsn'];
                $uName = $v['username'];
                $pass = $v['password'];
                $opts = $v['options'];
                $default = function () use ($dsn, $uName, $pass, $opts) {
                    return new ExtendedPdo(
                        $dsn,
                        $uName,
                        $pass,
                        $opts
                    );
                };
            } elseif (strtolower($k) == 'read') {
                foreach ($k as $k2 => $v2) {
                    $types['read'][$k2] = $v2['type'];
                    $dsn = $v2['dsn'];
                    $uName = $v2['username'];
                    $pass = $v2['password'];
                    $opts = $v2['options'];
                    $read[$k2] = function () use ($dsn, $uName, $pass, $opts) {
                        return new ExtendedPdo(
                            $dsn,
                            $uName,
                            $pass,
                            $opts
                        );
                    };
                }
            } elseif (strtolower($k) == 'write') {
                foreach ($k as $k2 => $v2) {
                    $types['write'][$k2] = $v2['type'];
                    $dsn = $v2['dsn'];
                    $uName = $v2['username'];
                    $pass = $v2['password'];
                    $opts = $v2['options'];
                    $write[$k2] = function () use ($dsn, $uName, $pass, $opts) {
                        return new ExtendedPdo(
                            $dsn,
                            $uName,
                            $pass,
                            $opts
                        );
                    };
                }
            }
        }

        $this->types = $types;

        // configure locator at construction time
        $this->locator = new ConnectionLocator($default, $read, $write);
    }
}