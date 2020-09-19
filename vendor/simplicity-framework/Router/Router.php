<?php

namespace Sim\Router;

use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use Sim\Abstracts\Mvc\Controller\Middleware\AbstractMiddleware;
use Sim\Container\Container;
use Sim\Exceptions\Router\RouterException;
use Sim\Interfaces\Router\IRouter;
use Symfony\Component\HttpFoundation\Request as Request;
use Symfony\Component\HttpFoundation\Response as Response;

class Router implements IRouter
{
    /**
     * @var array $http_methods
     */
    protected $http_methods = [
        'HEAD',
        'GET',
        'POST',
        'PUT',
        'PATCH',
        'DELETE',
        'PURGE',
        'OPTIONS',
        'TRACE',
        'CONNECT',
    ];

    /**
     * @var Request|null $request
     */
    protected $request = null;

    /**
     * @var Response|null
     */
    protected $response = null;

    /**
     * @var array $method_parameters
     */
    protected $method_parameters = [];

    /**
     * @var array $matched_parameters
     */
    protected $matched_parameters = [];

    /**
     * @var array $middleware
     */
    protected $middleware = [];

    /**
     * @var AbstractMiddleware $middleware_bottleneck
     */
    protected $middleware_bottleneck;

    /**
     * @var \Closure|null $middleware_error
     */
    protected $middleware_error = null;

    /**
     * @var array $errors
     */
    protected $errors = [];

    /**
     * @var array $url
     */
    protected $url = ['/'];

    /**
     * @var string $match_regex
     */
    protected $match_regex = '#^(?<regex>.*\:)?(?<route>\w+\d*)(?<default>\[.*\])?(?<optional>\?)?$#i';

    /**
     * @var string $split_regex
     */
    protected $split_regex = '#(?<split>[^\/]+(?:[^\/]*)*)#i';

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->response = new Response();
        $this->request = Request::createFromGlobals();
        $this->parseUrl();
    }

    /**
     * @param string $route
     * @param callable|string $callback
     * @return Router
     * @throws RouterException
     */
    public function any(string $route, $callback)
    {
        $this->prepareParameters($this->http_methods, $route, $callback);
        return $this;
    }

    /**
     * @param array|string $method
     * @param array|string $route
     * @param \Closure|string $callback
     * @return Router
     * @throws RouterException
     */
    public function addRoute($method, $route, $callback)
    {
        $this->prepareParameters($method, $route, $callback);
        return $this;
    }

    /**
     * @param $method
     * @param $route
     * @param $parameter_name
     * @param $type
     * @return Router
     */
    public function setParameterType($method, $route, $parameter_name, $type)
    {
        $this->setValueToParameter($method, $route, $parameter_name, 'regex', $type);
        return $this;
    }

    /**
     * @param $method
     * @param $route
     * @param $parameter_name
     * @param $default
     * @return Router
     */
    public function setParameterDefault($method, $route, $parameter_name, $default)
    {
        $this->setValueToParameter($method, $route, $parameter_name, 'default', $default);
        return $this;
    }

    /**
     * Match current url to all routes
     *
     * @throws ReflectionException
     * @throws RouterException
     */
    public function match()
    {
        $method = $this->request->getMethod();
        $url = $this->url;
        $route = implode('/', $url);
        $method_arr = $this->method_parameters[strtolower($method)] ?? [];
        foreach ($method_arr as $routeStr) {
            $match_phrase = '';
            $this->matched_parameters = [];
            foreach ($routeStr['parameters'] as $k => $parameter) {
                if ($parameter['is_parameter']) {
                    if (isset($url[$k])) {
                        $this->matched_parameters[$parameter['route']] = $url[$k];
                    } else {
                        $this->matched_parameters[$parameter['route']] = $parameter['default'];
                    }
                    if (!empty($parameter['regex']) && isset($url[$k])) {
                        $match_phrase .= $parameter['regex'];
                    } elseif (!$parameter['is_optional'] && is_null($parameter['default'])) {
                        $match_phrase .= '^(?:.*)';
                    }
                    if (!isset($url[$k]) && !is_null($parameter['default'])) {
                        $url[$k] = $parameter['default'];
                        $route = implode('/', $url);
                    }
                } else {
                    $match_phrase .= preg_quote($parameter['route']);
                }
                $match_phrase .= '/';
            }
            $match_phrase = $this->formatRoute($match_phrase);
            $result = preg_match('#' . $match_phrase . '#i', $route, $matched);
            if ($result) {
                $this->makeMiddlewareNested();
                $middleware_result = $this->middlewareResult();
                if ($middleware_result) {
                    if ($routeStr['callback'] instanceof \Closure) {
                        $this->closureRunner($routeStr['callback']);
                    } else {
                        $this->classRunner($routeStr['callback']['controller'], $routeStr['callback']['method']);
                    }
                } else {
                    $this->closureRunner($this->middleware_error);
                }
                return;
            }
        }
        $this->matched_parameters = [];
    }

    /**
     * @param $name
     * @param $args
     * @return Router
     * @throws RouterException
     */
    public function __call($name, $args)
    {
        list($route, $callback) = $args;
        $this->prepareParameters($name, $route, $callback);
        return $this;
    }

    /**
     * @param array|string $middleware
     * @return Router
     */
    public function middleware($middleware)
    {
        if (is_array($middleware)) {
            foreach ($middleware as $item) {
                if (is_string($item)) {
                    $this->addMiddleware($middleware);
                }
            }
        } elseif (is_string($middleware)) {
            $this->addMiddleware($middleware);
        }
        return $this;
    }

    /**
     * @param \Closure $handler
     */
    public function middlewareValidationError(\Closure $handler)
    {
        $this->middleware_error = $handler;
    }

    /**
     * A dummy debug function
     */
    public function debug()
    {
        echo "<pre>";
        print_r($this->method_parameters);
        echo "</pre>";
        echo "\n";
        echo "<pre>";
        print_r($this->matched_parameters);
        echo "</pre>";
    }

    /**
     * @param $method
     * @param $route
     * @param $callback
     * @throws RouterException
     */
    protected function prepareParameters($method, $route, $callback)
    {
        $is_ok = true;
        $parameters = [];

        // validate request method(s)
        if (is_array($method)) {
            foreach ($method as $k => $name) {
                if (!is_string($name) || !$this->validateMethod($name)) {
                    $this->invalidMethodHandler();
                    $this->errors['method'] = "Method {$method}() doesn't exists";
                    $is_ok = false;
                    break;
                } else {
                    if (!isset($parameters['method'])) {
                        $parameters['method'] = [];
                    }
                    $parameters['method'][] = $name;
                }
            }
        } elseif (is_string($method)) {
            if ($this->validateMethod($method)) {
                $parameters['method'] = [$method];
            } else {
                $this->invalidMethodHandler();
                $this->errors['method'] = "Method {$method}() doesn't exists";
                $is_ok = false;
            }
        }

        // validate route string/array
        if (!is_string($route) && !is_array($route)) {
            $this->errors['route'] = "Route variable must be string, " . gettype($route) . " given";
            $is_ok = false;
        } else {
            if (is_array($route)) {
                foreach ($route as $name) {
                    if (!isset($parameters['route'])) {
                        $parameters['route'] = [];
                    }
                    $parameters['route'][] = $this->formatRoute($name);
                }
            } else {
                $parameters['route'] = [$this->formatRoute($route)];
            }
            $parameters['route_parameters'] = [];
            foreach ($parameters['route'] as $routeStr) {
                $parameters['route_parameters'][$routeStr] = $this->extractRouteParameters($routeStr);
            }
        }

        // validate callback string/closure
        if (!$callback instanceof \Closure) {
            if ((is_string($callback) && 2 != count(explode('@', $callback))) || !is_string($callback)) {
                $this->errors['callback'] = "Callback must be type of callable or string, " . gettype($callback) . " given";
                $is_ok = false;
            } elseif (is_string($callback)) {
                $split = explode('@', $callback);
                $parameters['callback']['controller'] = $split[0] ?? null;
                $parameters['callback']['method'] = $split[1] ?? null;
            }
        } else {
            $parameters['callback'] = $callback;
        }

        if (!$is_ok) {
            $messages = '';
            foreach ($this->errors as $error) {
                $messages .= $error . PHP_EOL;
            }
            throw new RouterException("Please check following error(s): \n" . $messages);
        }

        // store detected values to its method variable
        foreach ($parameters['route'] as $routeStr) {
            foreach ($parameters['method'] as $methodStr) {
                $this->method_parameters[strtolower($methodStr)][$routeStr] = [
                    'parameters' => $parameters['route_parameters'][$routeStr],
                    'callback' => $parameters['callback'],
                ];
            }
        }
    }

    /**
     * @param $routes
     * @return array
     */
    protected function extractRouteParameters($routes)
    {
        $extractedParameters = [];
        $counter = 0;
        preg_match_all($this->split_regex, $routes, $split);
        foreach ($split['split'] as $route) {
            // if route is a parameter
            if (0 === strpos($route, '{') && (mb_strlen($route) - 1) === strpos($route, '}')) {
                $route = trim($route, '{');
                $route = trim($route, '}');
                preg_match($this->match_regex, $route, $matches);
                $extractedParameters[$counter]['regex'] = rtrim($matches['regex'], ':');
                $extractedParameters[$counter]['is_optional'] = !empty($matches['optional']);
                $extractedParameters[$counter]['is_parameter'] = true;
                $matches['default'] = !empty($matches['default']) ? trim(trim(trim($matches['default'], ']'), '[')) : null;
                $extractedParameters[$counter]['default'] = !empty($matches['default']) ? $matches['default'] : null;
            } else { // if route is just a name
                preg_match($this->match_regex, $route, $matches);
                $extractedParameters[$counter]['is_parameter'] = false;
            }
            if (empty($matches['route'])) {
                unset($extractedParameters[$counter]);
            } else {
                $extractedParameters[$counter]['route'] = $matches['route'];
                ++$counter;
            }
        }
        return $extractedParameters;
    }

    /**
     * @param $method
     * @param $route
     * @param $parameter_name
     * @param $key
     * @param $value
     */
    protected function setValueToParameter($method, $route, $parameter_name, $key, $value): void
    {
        $method_arr = $this->method_parameters[strtolower($method)] ?? null;
        if (!is_null($method_arr)) {
            if (isset($method_arr[$route])) {
                foreach ($method_arr[$route]['parameters'] as &$parameter) {
                    if ($parameter['is_parameter'] && $parameter_name == $parameter['route']) {
                        $parameter[$key] = $value;
                    }
                }
            }
        }
    }

    /**
     * @param $middleware
     */
    protected function addMiddleware($middleware)
    {
        if (false === array_search($middleware, $this->middleware)) {
            if (\container()->has($middleware)) {
                $this->middleware[] = $middleware;
            }
        }
    }

    /**
     * Make all middleware inside each other to check them by calling one of them
     */
    protected function makeMiddlewareNested()
    {
        if (count($this->middleware)) {
            $main_middleware = $this->middleware[0];
            $middleware_collection = array_diff([$main_middleware], $this->middleware);
            $this->middleware_bottleneck = \container()->get($main_middleware);
            foreach ($middleware_collection as $middleware) {
                $this->middleware_bottleneck->linkWith($middleware);
            }
        }
    }

    /**
     * @return bool
     */
    protected function middlewareResult(): bool
    {
        if (!count($this->middleware)) return true;
        return $this->middleware_bottleneck->handle($this->request);
    }

    /**
     * @param \Closure $closure
     * @throws ReflectionException
     */
    protected function closureRunner(\Closure $closure)
    {
        $reflection = new ReflectionFunction($closure);

        $call_result = call_user_func_array($closure, [$arguments]);
        $this->response->setContent($call_result)->send();
    }

    /**
     * @param $class
     * @param $method
     * @throws ReflectionException
     * @throws RouterException
     */
    protected function classRunner($class, $method)
    {
        $reflector = $this->getReflector($class);
        $has_method = $reflector->hasMethod($method);
        $has_method = $has_method && $reflector->getMethod($method)->isPublic();
        if ($has_method) {
            $detected_class = \container()->get($class);
            $detected_method = $method;
            $method_result = $detected_class->{$detected_method}($this->request);
            if (!is_null($method_result) && is_string($method_result)) {
                $this->response->setContent($method_result)->send();
            }
        }
    }

    /**
     * Get a reflection class for a specific entry/concrete
     *
     * @param $entry
     * @return ReflectionClass
     * @throws RouterException
     */
    protected function getReflector($entry)
    {
        try {
            $reflector = new ReflectionClass($entry);
            if (!$reflector->isInstantiable()) { // Check if class is instantiable
                throw new RouterException($entry);
            }
            return $reflector; // Return class reflector
        } catch (ReflectionException $ex) {
            throw new RouterException($entry);
        }
    }

    /**
     * @param $route
     * @return string
     */
    protected function formatRoute($route)
    {
        $result = is_string($route) ? $route : '/';
        $result = rtrim($result, '/');
        $result = '' === $result ? '/' : $result;
        return $result;
    }

    /**
     * @param $method
     * @return bool
     */
    protected function validateMethod($method)
    {
        return in_array(strtoupper($method), $this->http_methods);
    }

    /**
     * Set header with status 405 method not allowed
     */
    protected function invalidMethodHandler()
    {
        header('HTTP/' . $this->response->getProtocolVersion() . ' ' . $this->response::HTTP_METHOD_NOT_ALLOWED . ' Method Not Allowed');
    }

    /**
     * Parse a given url
     */
    protected function parseUrl(): void
    {
        $url = $this->request->get('url');
        if (!is_null($url)) {
            $this->url = array_map('trim', explode('/', urldecode(filter_var(urlencode(rtrim($url, '/')), FILTER_SANITIZE_URL))));
        }
    }
}