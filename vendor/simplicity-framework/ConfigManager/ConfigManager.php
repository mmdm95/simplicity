<?php

namespace Sim\ConfigManager;

use Sim\Interfaces\ConfigManager\IConfig;
use Sim\Traits\TraitConfigManager;

class ConfigManager implements IConfig
{
    use TraitConfigManager;
}