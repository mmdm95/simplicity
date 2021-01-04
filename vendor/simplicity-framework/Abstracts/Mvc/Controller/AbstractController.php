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
     * @var string $layout
     */
    protected $layout = '';

    /**
     * @var string $template
     */
    protected $template;

    /**
     * @var array $default_arguments
     */
    protected $default_arguments = [];

    /**
     * @var array $middlewares
     */
    protected $middlewares = [];

    /**
     * @var array $middlewareParameters
     */
    private $middlewareParameters = [];

    /**
     * @var AbstractMiddleware $middleware_bottleneck
     */
    private $middleware_bottleneck;

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
     * {@inheritdoc}
     */
    public function setMiddleWare($middleWares, ?array $parameters = null)
    {
        if (\is_array($middleWares)) {
            foreach ($middleWares as $middleWare) {
                if (!\in_array($middleWare, $this->middlewares)) {
                    $this->middlewares[] = $middleWare;
                }
            }
        } elseif (\is_string($middleWares)) {
            if (!\in_array($middleWare, $this->middlewares)) {
                $this->middlewares[] = $middleWares;
            }
        }
        if (!is_null($parameters)) {
            $this->middlewareParameters = $parameters;
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeMiddleware(string $middleware)
    {
        if (isset($this->middlewares[$middleware])) {
            unset($this->middlewares[$middleware]);
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeAllMiddlewares()
    {
        $this->middlewares = [];
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasMiddleware(): bool
    {
        return \count($this->middlewares);
    }

    /**
     * {@inheritdoc}
     */
    public function middlewareResult(): bool
    {
        $this->makeMiddlewareNested();
        if (!$this->hasMiddleware()) return true;
        return $this->middleware_bottleneck->handle($this->request);
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
        $arguments = array_replace_recursive($this->default_arguments, $arguments);

        $allow = !$this->hasMiddleware() ? true : $this->middlewareResult();
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
        $this->removeAllMiddlewares()->isJson(false);

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
        $this->removeAllMiddlewares()->isJson(false);

        header_remove("Content-Type");
        \response()->httpCode(500)->header('HTTP/1.1 500 Internal Server Error');
        return $this->render($arguments);
    }

    /**
     * Make all middleware inside each other to check them by calling one of them
     */
    private function makeMiddlewareNested()
    {
        if ($this->hasMiddleware()) {
            $main_middleware = $this->middlewares[0];
            $middleware_collection = \ array_diff([$main_middleware], $this->middlewares);
            $this->middleware_bottleneck = \container()->get($main_middleware);
            if ($this->middleware_bottleneck instanceof AbstractMiddleware) {
                foreach ($middleware_collection as $middleware) {
                    $newMiddleware = \container()->get($middleware);
                    if ($newMiddleware instanceof AbstractMiddleware) {
                        $this->middleware_bottleneck->linkWith($middleware);
                    } else {
                        $this->removeMiddleware($middleware);
                    }
                }
            } else {
                $this->removeAllMiddlewares();
            }
        }
    }
}