<?php

namespace Sim\Loader;

use Sim\Abstracts\Patterns\AbstractSingleton;
use Sim\Interfaces\Loader\ILoader;
use Sim\Traits\TraitLoader;

class LoaderSingleton extends AbstractSingleton implements ILoader
{
    use TraitLoader;
}