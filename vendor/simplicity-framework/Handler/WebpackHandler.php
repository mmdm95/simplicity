<?php

namespace Sim\Handler;

use Sim\File\FileSystem;
use Sim\File\Filters\RegexFilter;
use Sim\Interfaces\IRunnable;
use SplFileInfo;

class WebpackHandler implements IRunnable
{
    /**
     * @var string
     */
    private $base_dir;

    /**
     * @var string
     */
    private $build_dir;

    /**
     * @var array
     */
    private $config = [];

    /**
     * WebpackHandler constructor.
     * @param string $build_dir
     * @param array $config
     */
    public function __construct(string $build_dir, array $config)
    {
        if (empty($build_dir)) {
            throw new \InvalidArgumentException("Specified directory is empty string!");
        }
        $build_dir = ltrim($build_dir, '\\/');

        if (!FileSystem::fileExists(BASE_ROOT . $build_dir) || !is_dir(BASE_ROOT . $build_dir)) {
            throw new \InvalidArgumentException("Specified directory for webpack built assets, is not valid!");
        }

        $this->base_dir = get_path('public', '', true);
        $this->build_dir = BASE_ROOT . $build_dir;
        $this->config = $config;
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $excludeFilters = [];
        $excluded = $this->getExcludedFiles();
        $excludeFilters = array_merge($excludeFilters, $this->getExcludedFilesFilter($excluded));

        // iterate through config
        foreach ($this->config as $dir => $group) {
            foreach ($group as $op => $options) {
                if (is_array($options) && isset($options['test']) && is_string($options['test'])) {
                    $test = $options['test'];
                    $overwrite = isset($options['overwrite']) ? (bool)$options['overwrite'] : false;
                    $excluded = $options['exclude'] ?? null;
                    $filters = array_merge($excludeFilters, $this->getExcludedFilesFilter($excluded));
                    $filters[] = new RegexFilter($test);

                    // get all files with specific filters
                    $allFiles = $this->getFilteredFiles($this->build_dir, $filters);

                    /**
                     * @var SplFileInfo $file
                     */
                    foreach ($allFiles as $file) {
                        $this->copyOrMove($op, $dir, $file, $overwrite);
                    }
                }
            }
        }
    }

    /**
     * Get and remove exclude key
     *
     * @return string|array|null
     */
    private function getExcludedFiles()
    {
        // get and remove exclude key
        $excluded = $this->config['exclude'] ?? null;
        unset($this->config['exclude']);

        return $excluded;
    }

    /**
     * Add exclude filters for wanted files
     *
     * @param string|array $excluded
     * @return array
     */
    private function getExcludedFilesFilter($excluded): array
    {
        $excludedFilters = [];
        if (is_string($excluded)) {
            $excludedFilters[] = new RegexFilter($excluded);
        } elseif (is_array($excluded)) {
            $excluded = array_map('preg_quote', $excluded);
            $excludedFilters[] = new RegexFilter('~^(?!(' . implode('|', $excluded) . ')$).*~i');
        }

        return $excludedFilters;
    }

    /**
     * @param $dir
     * @param $filters
     * @return array
     */
    private function getFilteredFiles($dir, $filters): array
    {
        return FileSystem::getDirFilteredFiles($dir, $filters);
    }

    /**
     * @param string $op
     * @param string $to
     * @param SplFileInfo $file
     * @param bool $overwrite
     */
    private function copyOrMove(string $op, string $to, SplFileInfo $file, bool $overwrite): void
    {
        $to = trim($to, '\\/');

        switch (strtolower($op)) {
            case 'copy':
                FileSystem::copyFile(
                    $file->getPathname(),
                    $this->base_dir . $to . DIRECTORY_SEPARATOR . $file->getFilename(),
                    $overwrite);
                break;
            case 'move':
                FileSystem::moveFile(
                    $file->getPathname(),
                    $this->base_dir . $to . DIRECTORY_SEPARATOR . $file->getFilename(),
                    $overwrite);
                break;
        }
    }
}