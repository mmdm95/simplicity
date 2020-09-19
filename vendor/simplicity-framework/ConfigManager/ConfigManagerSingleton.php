<?php

namespace Sim\ConfigManager;

use Sim\Abstracts\Patterns\AbstractSingleton;
use Sim\Interfaces\ConfigManager\IConfig;
use Sim\Traits\TraitConfigManager;

class ConfigManagerSingleton extends AbstractSingleton implements IConfig
{
    use TraitConfigManager;
}