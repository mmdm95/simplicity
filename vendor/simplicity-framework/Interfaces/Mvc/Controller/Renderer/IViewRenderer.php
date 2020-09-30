<?php

namespace Sim\Interfaces\Mvc\Controller\Renderer;

use Sim\Interfaces\IRenderer;

interface IViewRenderer extends IRenderer
{
    /**
     * Render layout page and put template inside itself
     */
    public function renderLayout(): void;

    /**
     * Render template page
     */
    public function renderTemplate(): void;
}