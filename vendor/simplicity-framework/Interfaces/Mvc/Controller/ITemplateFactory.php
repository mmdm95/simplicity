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
     * @param AbstractMiddleware $middleWare
     * @param array $parameters
     * @return static
     */
    public function setMiddleWare(AbstractMiddleware $middleWare, array $parameters = []);
}