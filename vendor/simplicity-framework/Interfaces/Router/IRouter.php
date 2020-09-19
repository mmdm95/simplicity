<?php

namespace Sim\Interfaces\Router;


interface IRouter
{
    /**
     * @param string $route
     * @param \Closure|string $callback
     * @return mixed
     */
    public function any(string $route, $callback);

    /**
     * @param array|string $method
     * @param array|string $route
     * @param \Closure|string $callback
     * @return mixed
     */
    public function addRoute($method, $route, $callback);

    /**
     * @param $method
     * @param $route
     * @param $parameter_name
     * @param $type
     * @return mixed
     */
    public function setParameterType($method, $route, $parameter_name, $type);

    /**
     * @param $method
     * @param $route
     * @param $parameter_name
     * @param $default
     * @return mixed
     */
    public function setParameterDefault($method, $route, $parameter_name, $default);

    /**
     * Match current url to all routes
     */
    public function match();
}