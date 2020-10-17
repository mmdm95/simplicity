<?php

namespace App\Logic\Models;

use Aura\Sql\ExtendedPdoInterface;
use Sim\DBConnector;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

abstract class BaseModel
{
    /**
     * @var DBConnector
     */
    protected $connector;

    /**
     * @var ExtendedPdoInterface
     */
    protected $db;

    /**
     * Model constructor.
     */
    /**
     * BaseModel constructor.
     * @throws ConfigNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function __construct()
    {
        $this->connector = \connector();
        $this->db = $this->connector->getDb();
    }
}