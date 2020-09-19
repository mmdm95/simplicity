<?php

namespace Sim\Interfaces\Mvc\Controller;


use Sim\Interfaces\IRenderer;

interface ITemplateRenderer extends IRenderer
{
    /**
     * Get rendered content
     *
     * @param array $arguments
     * @return string
     */
    public function render(array $arguments = []): string;
}