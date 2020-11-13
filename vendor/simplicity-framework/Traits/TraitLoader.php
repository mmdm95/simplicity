<?php

namespace Sim\Traits;

use Sim\Exceptions\FileNotExistsException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\Loader\ILoader;

trait TraitLoader
{
    use TraitExtension;

    /**
     * @var $allowed_types array
     */
    protected $allowed_types = [
        ILoader::TYPE_INCLUDE,
        ILoader::TYPE_INCLUDE_ONCE,
        ILoader::TYPE_REQUIRE,
        ILoader::TYPE_REQUIRE_ONCE,
    ];

    /**
     * @var $allowed_extensions array
     */
    protected $allowed_extensions = [
        ILoader::EXT_PHP,
        ILoader::EXT_PHTML,
        ILoader::EXT_HTML,
    ];

    /**
     * @var $data array
     */
    protected $data = [];

    /**
     * @var int $default_type
     */
    protected $default_type = ILoader::TYPE_INCLUDE;

    /**
     * @var $extension string
     */
    protected $default_extension = ILoader::EXT_PHP;

    /**
     * Load a specific file with its filename
     *
     * @param string $filename
     * @param string|null $ext
     * @param int $type
     * @return static
     * @throws IFileNotExistsException
     */
    public function load(string $filename, ?string $ext = null, int $type = ILoader::TYPE_INCLUDE)
    {
        $this->_load($filename, $ext, $type, true);
        $this->setData([]);
        return $this;
    }

    /**
     * @param string $filename
     * @param string|null $ext
     * @param int $type
     * @return mixed
     * @throws FileNotExistsException
     */
    public function loadNReturn(string $filename, string $ext = null, int $type = ILoader::TYPE_INCLUDE)
    {
        $loadedFile = $this->_load($filename, $ext, $type, true);
        $this->setData([]);
        return $loadedFile;
    }

    /**
     * @param string $filename
     * @param string|null $ext
     * @return mixed|static
     * @throws IFileNotExistsException
     */
    public function load_include(string $filename, string $ext = null, bool $return_result = false)
    {
        if ($return_result) {
            return $this->loadNReturn($filename, $ext, ILoader::TYPE_INCLUDE);
        } else {
            return $this->load($filename, $ext, ILoader::TYPE_INCLUDE);
        }
    }

    /**
     * @param string $filename
     * @param string|null $ext
     * @return mixed|static
     * @throws IFileNotExistsException
     */
    public function load_include_once(string $filename, string $ext = null, bool $return_result = false)
    {
        if ($return_result) {
            return $this->loadNReturn($filename, $ext, ILoader::TYPE_INCLUDE_ONCE);
        } else {
            return $this->load($filename, $ext, ILoader::TYPE_INCLUDE_ONCE);
        }
    }

    /**
     * @param string $filename
     * @param string|null $ext
     * @return mixed|static
     * @throws IFileNotExistsException
     */
    public function load_require(string $filename, string $ext = null, bool $return_result = false)
    {
        if ($return_result) {
            return $this->loadNReturn($filename, $ext, ILoader::TYPE_REQUIRE);
        } else {
            return $this->load($filename, $ext, ILoader::TYPE_REQUIRE);
        }
    }

    /**
     * @param string $filename
     * @param string|null $ext
     * @return mixed|static
     * @throws IFileNotExistsException
     */
    public function load_require_once(string $filename, string $ext = null, bool $return_result = false)
    {
        if ($return_result) {
            return $this->loadNReturn($filename, $ext, ILoader::TYPE_REQUIRE_ONCE);
        } else {
            return $this->load($filename, $ext, ILoader::TYPE_REQUIRE_ONCE);
        }
    }

    /**
     * Get content of a file as string
     *
     * @param string $filename
     * @param string|null $ext
     * @return string
     * @throws IFileNotExistsException
     */
    public function getContent(string $filename, string $ext = null)
    {
        ob_start();
        extract($this->data);
        $this->load_include($filename, $ext);
        $result = ob_get_contents();
        ob_end_clean();
        $this->setData([]);
        return $result;
    }

    /**
     * Set data to pass included file.
     * Note: Use this function before load() method
     *
     * @param array $data
     * @return static
     */
    public function setData(array $data = [])
    {
        $this->data = is_array($data) ? $data : [];
        return $this;
    }

    /**
     * @param string $filename
     * @param string|null $ext
     * @param int $type
     * @param bool $return_loaded_file
     * @return mixed|static
     * @throws FileNotExistsException
     */
    private function _load(string $filename, string $ext = null, $type = ILoader::TYPE_INCLUDE, bool $return_loaded_file = false)
    {
        $filename = $this->addExtensionIfNotExist($filename, $ext, $this->default_extension, $this->allowed_extensions);
        $type = $this->_isValidType($type) ? $type : $this->default_type;

        if (!file_exists((string)$filename)) {
            throw new FileNotExistsException($filename);
        }

        extract($this->data);

        // Just for solve editor inception, added empty string end of filename
        // I had no choice, SORRY! :(
        switch ($type) {
            case ILoader::TYPE_REQUIRE_ONCE:
                $return = require_once $filename . '';
                break;
            case ILoader::TYPE_INCLUDE_ONCE:
                $return = include_once $filename . '';
                break;
            case ILoader::TYPE_REQUIRE:
                $return = require $filename . '';
                break;
            case ILoader::TYPE_INCLUDE:
            default:
                $return = include $filename . '';
                break;
        }
        $return = $return_loaded_file ? $return : $this;
        $this->setData([]);
        return $return;
    }

    /**
     * Check if a type of including file is valid type
     *
     * @param $type
     * @return bool
     */
    private function _isValidType($type)
    {
        return !empty($type) && in_array($type, $this->allowed_types);
    }
}