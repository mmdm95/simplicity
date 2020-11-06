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
     * @return ITemplateFactory
     */
    public function setLayout($path): ITemplateFactory;

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
     * @return ITemplateFactory
     */
    public function setTemplate($path): ITemplateFactory;

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
     * @return ITemplateFactory
     */
    public function isJson(bool $answer): ITemplateFactory;

    /**
     * Set middleware for current route
     *
     * @param AbstractMiddleware $middleWare
     * @param array $parameters
     * @return ITemplateFactory
     */
    public function setMiddleWare(AbstractMiddleware $middleWare, $parameters = []): ITemplateFactory;
}