<?php

namespace Sim\Mvc\Controller\Renderer;

use Sim\Abstracts\Mvc\Controller\Renderer\AbstractViewRenderer;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Loader\Loader;

class PHPRenderer extends AbstractViewRenderer
{
    /**
     * Render layout page and put template inside itself
     * @throws IFileNotExistsException
     */
    public function renderLayout(): void
    {
        if (!empty($this->layout)) {
            $content = $this->getContent();
            $this->rendered = $content;
        }
    }

    /**
     * Render template page
     * @throws IFileNotExistsException
     */
    public function renderTemplate(): void
    {
        if (empty($this->layout)) {
            $content = $this->getContent();
            $this->rendered = $content;
        } else {
            $this->arguments['content'] = $content;
        }
    }

    /**
     * @param string $content
     * @return bool
     */
    protected function getContent(): string
    {
        if (empty($this->layout)) {
            $filename = $this->template;
        } else {
            $filename = $this->layout;
        }

        return \loader()->setData($this->arguments)->getContent($filename);
    }
}