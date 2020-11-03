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
            $this->rendered = $this->getContent($this->layout);
        }
    }

    /**
     * Render template page
     * @throws IFileNotExistsException
     */
    public function renderTemplate(): void
    {
        $content = $this->getContent($this->template);
        if (empty($this->layout)) {
            $this->rendered = $content;
        } else {
            $this->arguments['content'] = $content;
        }
    }

    /**
     * @param string $content
     * @return bool
     */
    protected function getContent(string $filename): string
    {
        return \loader()->setData($this->arguments)->getContent($filename);
    }
}