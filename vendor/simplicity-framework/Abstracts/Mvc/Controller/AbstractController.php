<?php

namespace Sim\Abstracts\Mvc\Controller;

use Pecee\Http\Response;
use Pecee\SimpleRouter\SimpleRouter;
use Sim\Abstracts\Mvc\Controller\Middleware\AbstractMiddleware;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\ConfigManager\IConfig;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Interfaces\Mvc\Controller\ITemplateFactory;
use Sim\Interfaces\Mvc\Controller\ITemplateRenderer;
use Sim\Mvc\Controller\Renderer\PHPRenderer as ViewRenderer;
use Sim\Traits\TraitExtension;
use Sim\Traits\TraitGeneral;

abstract class AbstractController implements ITemplateFactory, ITemplateRenderer
{
    use TraitGeneral {
        isValidName as private;
    }
    use TraitExtension {
        addExtensionIfNotExist as private;
    }

    /**
     * @var $layout string
     */
    protected $layout = '';

    /**
     * @var $template string
     */
    protected $template;

    /**
     * @var array $default_arguments
     */
    protected $default_arguments = [];

    /**
     * @var $middleware AbstractMiddleWare
     */
    protected $middleware;

    /**
     * @var $middlewareParameters array
     */
    protected $middlewareParameters;

    /**
     * @var $middlewareLayout string
     */
    protected $middlewareLayout;

    /**
     * @var $middlewareTemplate string
     */
    protected $middlewareTemplate;

    /**
     * @var array $allowedExtensions
     */
    protected $allowedExtensions = ['php', 'phtml'];

    /**
     * @var string $defaultExtension
     */
    protected $defaultExtension = 'php';

    /**
     * To specify if rendered page must be as json string
     * @var $is_json bool
     */
    protected $is_json = false;

    /**
     * @var IConfig $config
     */
    protected $config;

    /**
     * @var bool $is_production
     */
    protected $is_in_production = false;

    /**
     * AbstractController constructor.
     * @throws ConfigNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function __construct()
    {
        $this->config = config();
        //-----
        $this->is_in_production = (0 == (($this->config->get('main.mode') ?? MODE_PRODUCTION) ^ MODE_PRODUCTION));
    }

    /**
     * Set layout of view
     *
     * @param $path
     * @return static
     */
    public function setLayout($path)
    {
        if (!is_null($path)) {
            if (empty($path)) {
                $this->layout = '';
            } else {
                $this->layout = $this->addExtensionIfNotExist(layout_path($path, false), $this->defaultExtension, $this->defaultExtension, $this->allowedExtensions);
            }
        }
        return $this;
    }

    /**
     * Get view layout
     *
     * @return string
     */
    public function getLayout(): string
    {
        return (string)$this->layout;
    }

    /**
     * Set template of view
     *
     * @param $path
     * @return static
     */
    public function setTemplate($path)
    {
        if (!empty($path)) {
            $this->template = $this->addExtensionIfNotExist(design_path($path, false), $this->defaultExtension, $this->defaultExtension, $this->allowedExtensions);
        }
        return $this;
    }

    /**
     * Get view template
     *
     * @return string
     */
    public function getTemplate(): string
    {
        return (string)$this->template;
    }

    /**
     * @param array $arguments
     * @return static
     */
    public function setDefaultArguments(array $arguments)
    {
        $this->default_arguments = $arguments;
        return $this;
    }

    /**
     * @return array
     */
    public function getDefaultArguments(): array
    {
        return $this->default_arguments;
    }

    /**
     * If the page must be render as json string or not
     *
     * @param bool $answer
     * @return static
     */
    public function isJson(bool $answer)
    {
        $this->is_json = (bool)$answer;
        return $this;
    }

    /**
     * Set middleware for current route
     *
     * @param AbstractMiddleware $middleWare
     * @param $parameters
     * @return static
     */
    public function setMiddleWare(AbstractMiddleware $middleWare, array $parameters = [])
    {
        if ($middleWare instanceof AbstractMiddleware) {
            $this->middleware = $middleWare;
            $this->middlewareParameters = $parameters;
        }
        return $this;
    }

    /**
     * Check if any middleware is register
     *
     * @return bool
     */
    public function hasMiddleware(): bool
    {
        if ($this->middleware instanceof AbstractMiddleware) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isInProductionMode(): bool
    {
        return (bool)$this->is_in_production;
    }

    /**
     * Get rendered content
     *
     * @param array $arguments
     * @param \Closure|null $middlewareErrorCallback
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws \ReflectionException
     * @throws PathNotRegisteredException
     */
    public function render(array $arguments = [], $middlewareErrorCallback = null): string
    {
        $arguments = array_merge_recursive($this->default_arguments, $arguments);

        $allow = !$this->hasMiddleware() ? true : $this->middleware->handle(...$this->middlewareParameters);
        if ($allow) {
            // Check if want json string
            if (true === $this->is_json) {
                \response()->json($arguments);
                return '';
            }

            $view = new ViewRenderer($this->getLayout(), $this->getTemplate(), $arguments);
            $page = $view->render();
            return $page;
        }
        if ($middlewareErrorCallback instanceof \Closure) {
            $reflection = new \ReflectionFunction($middlewareErrorCallback);
            $parameters = $reflection->getParameters();
            $call_result = call_user_func_array($middlewareErrorCallback, $parameters);
            if (!is_null($call_result)) return $call_result;
        }
        return $this->show404($arguments);
    }

    /**
     * Show 404 page to end user
     *
     * @param array $arguments
     * @param string|null $layout
     * @param string $template
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \ReflectionException
     */
    public function show404(array $arguments = [], ?string $layout = null, string $template = ''): string
    {
        $routerConfig = $this->config->get('router');
        $this->setLayout(!is_null($layout) ? $layout : (!is_null($routerConfig['notfound_route']['layout']) ? $routerConfig['notfound_route']['layout'] : ''));
        // Check for not found template that at least set in here or in default config
        if (empty($template) && (!isset($routerConfig['notfound_route']['template']) || empty($routerConfig['notfound_route']['template']))) {
            throw new ControllerException('Not found page must have a template to show! Please add it here or in router config.');
        }
        $this->setTemplate(!empty($template) ? $template : (!empty($routerConfig['notfound_route']['template']) ? $routerConfig['notfound_route']['template'] : null));
        $this->removeMiddleware()->isJson(false);

        header_remove("Content-Type");
        \response()->httpCode(404)->header('HTTP/1.1 404 Not Found');
        return $this->render($arguments);
    }

    /**
     * Show 500 page to end user
     *
     * @param array $arguments
     * @param string|null $layout
     * @param string $template
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \ReflectionException
     */
    public function show500(array $arguments = [], ?string $layout = null, string $template = ''): string
    {
        $routerConfig = $this->config->get('router');
        $this->setLayout(!is_null($layout) ? $layout : (!is_null($routerConfig['error_route']['layout']) ? $routerConfig['error_route']['layout'] : ''));
        // Check for not found template that at least set in here or in default config
        if (empty($template) && (!isset($routerConfig['error_route']['template']) || empty($routerConfig['error_route']['template']))) {
            throw new ControllerException('Not found page must have a template to show! Please add it here or in router config.');
        }
        $this->setTemplate(!empty($template) ? $template : (!empty($routerConfig['error_route']['template']) ? $routerConfig['error_route']['template'] : null));
        $this->removeMiddleware()->isJson(false);

        header_remove("Content-Type");
        \response()->httpCode(500)->header('HTTP/1.1 500 Internal Server Error');
        return $this->render($arguments);
    }

    /**
     * Remove all middleware
     *
     * @return AbstractController
     */
    protected function removeMiddleware(): AbstractController
    {
        $this->middleware = null;
        return $this;
    }
}