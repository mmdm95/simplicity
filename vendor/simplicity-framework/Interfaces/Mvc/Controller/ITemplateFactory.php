<?php

namespace Sim\Interfaces\Mvc\Controller;


use Sim\Abstracts\Mvc\Controller\Middleware\AbstractMiddleware;
use Sim\Interfaces\IRenderer;

interface ITemplateFactory extends IRenderer
{
    /**
     * Set layout of view
     *
     * @param $path
     * @return static
     */
    public function setLayout($path);

    /**
     * Get view layout
     *
     * @return string
     */
    public function getLayout(): string;

    /**
     * Set template of view
     *
     * @param $path
     * @return static
     */
    public function setTemplate($path);

    /**
     * Get view template
     *
     * @return string
     */
    public function getTemplate(): string;

    /**
     * If the page must be render as json string or not
     *
     * @param bool $answer
     * @return static
     */
    public function isJson(bool $answer);

    /**
     * Set middleware for current route
     *
     * @param array|string $middleWares
     * @param array $parameters
     * @return static
     */
    public function setMiddleWare($middleWares, array $parameters = []);

    /**
     * Check if any middleware is register
     *
     * @return bool
     */
    public function hasMiddleware(): bool;

    /**
     * Remove specific middleware
     *
     * @return static
     */
    public function removeMiddleware(string $middleware);
    
    /**
     * Remove all middleware
     *
     * @return static
     */
    public function removeAllMiddlewares();

    /**
     * @return bool
     */
    public function middlewareResult(): bool;
}