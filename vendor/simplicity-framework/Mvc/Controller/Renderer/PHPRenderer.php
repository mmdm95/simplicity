<?php

namespace Sim\Mvc\Controller\Renderer;

use Sim\Abstracts\Mvc\Controller\Renderer\AbstractViewRenderer;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Loader\Loader;

class PHPRenderer extends AbstractViewRenderer
{
    public function __construct($layout, $template, array $arguments = [])
    {
        parent::__construct($layout, $template, $arguments);
    }

    /**
     * Render layout page and put template inside itself
     * @throws IFileNotExistsException
     */
    public function renderLayout(): void
    {
        if (!empty($this->layout)) {
            $this->rendered = $this->getContent($this->layout);
        }
    }

    /**
     * Render template page
     * @throws IFileNotExistsException
     */
    public function renderTemplate(): void
    {
        if (empty($this->layout)) {
            $this->rendered = $this->getContent($this->template);
        } else {
            $this->arguments['content'] = $this->getContent($this->template);
        }
    }

    /**
     * Get content of a file
     *
     * @param $path
     * @return string
     * @throws IFileNotExistsException
     */
    public function getContent($path): string
    {
        return \loader()->setData($this->arguments)->getContent($path);
    }
}