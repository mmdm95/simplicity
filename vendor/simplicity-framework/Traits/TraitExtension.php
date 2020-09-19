<?php

namespace Sim\Traits;

trait TraitExtension
{
    /**
     * Add extension to a path if that path didn't have any valid extension
     *
     * @param string $path
     * @param string|null $ext
     * @param string $defaultExtension
     * @param array $allowedExtensions
     * @return string
     */
    protected function addExtensionIfNotExist($path, $ext = null, $defaultExtension = 'php', $allowedExtensions = []): string
    {
        $ext = $this->_extToLower($ext);
        $tmpExtSepArr = explode(".", $path);
        $extension = $this->_extToLower(end($tmpExtSepArr));
        if ($ext != $extension && (empty($allowedExtensions) || !in_array($extension, $allowedExtensions))) {
            $path = $path . '.' . (!empty($ext) && (empty($allowedExtensions) || in_array($ext, $allowedExtensions))
                    ? $ext
                    : $this->_extToLower($defaultExtension));
        }

        return $path;
    }

    /**
     * Make extension to lowercase with mb extension
     *
     * @param $ext
     * @return string
     */
    private function _extToLower($ext)
    {
        return mb_strtolower((string)$ext);
    }
}