<?php

namespace Sim\Core;

use App\Logic\Adapter\SessionTokenProvider;
use App\Logic\Container as ContainerDefinition;
use App\Logic\Route as RouteDefinition;
use App\Logic\Event as EventDefinition;
use App\Logic\Helper as HelperDefinition;

use Sim\ConfigManager\ConfigManager;
use Sim\ConfigManager\ConfigManagerSingleton;
use Sim\Container\Container;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;

use Sim\Cookie\Cookie;

use Sim\Crypt\Crypt;
use Sim\Event\Emitter;
use Sim\I18n\Translate;
use Sim\Interfaces\ConfigManager\IConfig;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Interfaces\Loader\ILoader;
use Sim\Interfaces\PathManager\IPath;

use Sim\Loader\Loader;

use Sim\Loader\LoaderSingleton;
use Sim\Logger\Logger;
use Sim\Session\Session as Session;

use Pecee\Http\Middleware\BaseCsrfVerifier;
use Pecee\SimpleRouter\SimpleRouter as Router;

use Dotenv\Dotenv;

class Bootstrap
{
    const REQUIRED_PHP_VERSION = '7.2';

    /**
     * @var ILoader $loader
     */
    protected $loader;

    /**
     * @var IPath $path
     */
    protected $path;

    /**
     * @var IConfig $config
     */
    protected $config;

    /**
     * @var bool $route_needed
     */
    protected $route_needed = true;

    /**
     * Bootstrap constructor.
     * @param bool $route_needed
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \Pecee\Http\Middleware\Exceptions\TokenMismatchException
     * @throws \Pecee\Http\Security\Exceptions\SecurityException
     * @throws \Pecee\SimpleRouter\Exceptions\HttpException
     * @throws \Pecee\SimpleRouter\Exceptions\NotFoundHttpException
     * @throws \ReflectionException
     */
    public function __construct(bool $route_needed = true)
    {
        $this->route_needed = $route_needed;
        //-----
        $this->defineConstants();
        $this->init();
        if ($route_needed) {
            $this->customErrorHandler();
            $this->defineRoute();
        }
    }

    /**
     * Initializer
     *
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    protected function init()
    {
        // Needed helpers
        $this->loadHelper();

        // Check for version compatibility
        if (!is_version_compatible(self::REQUIRED_PHP_VERSION)) {
            die('Your php version is not compatible with requirements of using this framework! Please upgrade your php to version ' . self::REQUIRED_PHP_VERSION . ' or higher');
        }

        // Start session and other things that need start at first
        if (PHP_SESSION_NONE == session_status()) {
            session_start();
        }

        // Get needed objects from Container class
        $this->loader = loader();
        $this->path = path();
        $this->config = config();

        // Call needed functionality
        $this->defineConfig();
        $this->definePath();
        // only for none route (without CLI) cases
        if ($this->route_needed) {
            $this->defineEvents();
            $this->defineContainer();
        }

        // Load .env variables
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        // Validate .env variables
        $dotenv->required('APP_MAIN_KEY')->notEmpty();
        $dotenv->required('APP_ASSURED_KEY')->notEmpty();
        $dotenv->required('DB_HOST')->notEmpty();
        $dotenv->required('DB_NAME')->notEmpty();
        $dotenv->required('DB_USERNAME')->notEmpty();
        $dotenv->required('DB_PASSWORD');
        $dotenv->required('DB_PORT')->isInteger();

        $this->config->setAsConfig('env', [
            'APP_MAIN_KEY' => getenv('APP_MAIN_KEY'),
            'APP_ASSURED_KEY' => getenv('APP_ASSURED_KEY'),
            'DB_HOST' => getenv('DB_HOST'),
            'DB_NAME' => getenv('DB_NAME'),
            'DB_USERNAME' => getenv('DB_USERNAME'),
            'DB_PASSWORD' => getenv('DB_PASSWORD'),
            'DB_PORT' => getenv('DB_PORT'),
        ]);
    }

    /**
     * Define framework constants
     */
    protected function defineConstants()
    {
        //******* Error Handler *******
        defined("E_FATAL") OR define("E_FATAL", E_ERROR | E_USER_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_RECOVERABLE_ERROR);

        //*********** Modes ***********
        defined("MODE_DEVELOPMENT") OR define("MODE_DEVELOPMENT", 0x1);
        defined("MODE_PRODUCTION") OR define("MODE_PRODUCTION", 0x2);
    }

    /**
     * Define all paths
     *
     * @throws IFileNotExistsException
     */
    protected function definePath()
    {
        // Load constants first
        LoaderSingleton::getInstance()->load(__DIR__ . '/../config/constants.php', null, Loader::TYPE_REQUIRE_ONCE);
        // Then load path config
        $paths = ConfigManagerSingleton::getInstance()->getDirectly(__DIR__ . '/../config/path.php');

        // Add all of them to path collector if $path is an array
        if (is_array($paths)) {
            foreach ($paths as $alias => $path) {
                if (is_string($path) && 'default_config' != $path) {
                    $this->path->set((string)$alias, (string)$path);
                }
            }
        }
    }

    /**
     * Load helpers that are not class but functions
     *
     * @throws IFileNotExistsException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    protected function loadHelper()
    {
        LoaderSingleton::getInstance()->load_require_once(__DIR__ . '/../vendor/simplicity-framework/Functions/appHelper.php');
        LoaderSingleton::getInstance()->load_require_once(__DIR__ . '/../vendor/simplicity-framework/Functions/generalHelper.php');
        LoaderSingleton::getInstance()->load_require_once(__DIR__ . '/../vendor/simplicity-framework/Functions/pathHelper.php');
        LoaderSingleton::getInstance()->load_require_once(__DIR__ . '/../vendor/simplicity-framework/Functions/routerHelper.php');

        /**
         * @var HelperDefinition $helper
         */
        $helper = Container::getInstance()->get(HelperDefinition::class);
        $helpers = $helper->init();
        foreach ($helpers as $h) {
            if (is_string($h)) {
                LoaderSingleton::getInstance()->load_require_once($h);
            }
        }
    }

    /**
     * Define config alias and directories
     */
    protected function defineConfig()
    {
        // Get config paths
        $configs = $this->config->getDirectly(__DIR__ . '/../config/path.php')['default_config'] ?? [];

        // Add all of them to config collector if $configs is an array
        if (is_array($configs)) {
            foreach ($configs as $alias => $path) {
                $this->config->set($alias, $path);
            }
        }
    }

    /**
     * Custom error handler initialization
     *
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    protected function customErrorHandler()
    {
        /**
         * @var ErrorHandler $errorHandler
         */
        $errorHandler = \container()->get(ErrorHandler::class);
        $errorHandler->init();
    }

    /**
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    protected function defineEvents()
    {
        // Read all events that defined by user
        /**
         * @var EventDefinition $event
         */
        $event = \container()->get(EventDefinition::class);
        $closures = $event->closures();
        $events = $event->events();

        // Define emitter to container
        \container()->set(Emitter::class, function () use ($closures, $events) {
            return new Emitter($events, $closures);
        });
    }

    /**
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    protected function defineContainer()
    {
        // Define container's object(s)

        \container()->set(Crypt::class, function () {
            return new Crypt($this->config->get('security.main_key'),
                $this->config->get('security.assured_key'));
        });

        \container()->set(Session::class, function (Container $c) {
            return new Session($c->get(Crypt::class));
        });

        \container()->set(Cookie::class, function (Container $c) {
            return new Cookie($c->get(Crypt::class));
        });

        \container()->set(ErrorHandler::class, function (Container $c) {
            return new ErrorHandler($c->get(ConfigManager::class), $c->get(Loader::class), $c->get(Logger::class));
        });

        \container()->set(Translate::class, function () {
            $translate = new Translate();
            $translateConfig = config()->get('i18n');
            if (!is_null($translateConfig)) {
                /** @var array $translateConfig */
                $translate->setTranslateDir($translateConfig['language_dir'] ?? '')
                    ->setLocale($translateConfig['language'] ?? '');

                if ((bool)config()->get('i18n.is_rtl') === true) {
                    $translate->itIsRTL();
                }
            }

            return $translate;
        });

        // Read all container objects that defined by user
        /**
         * @var ContainerDefinition $container
         */
        $container = \container()->get(ContainerDefinition::class);
        $container->init();
    }

    /**
     * Read routes and
     *
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \Pecee\Http\Middleware\Exceptions\TokenMismatchException
     * @throws \Pecee\Http\Security\Exceptions\SecurityException
     * @throws \Pecee\SimpleRouter\Exceptions\HttpException
     * @throws \Pecee\SimpleRouter\Exceptions\NotFoundHttpException
     * @throws \ReflectionException
     * @throws MethodNotFoundException
     * @throws \Exception
     */
    protected function defineRoute()
    {
        $verifier = new BaseCsrfVerifier();
        $verifier->setTokenProvider(new SessionTokenProvider());

        Router::csrfVerifier($verifier);

        /**
         * @var RouteDefinition $route
         */
        $route = \container()->get(RouteDefinition::class);
        $route->init();

        // Start the routing
        Router::start();
    }
}
