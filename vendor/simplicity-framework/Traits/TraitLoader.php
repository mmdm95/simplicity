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
     * @var $type string
     */
    protected $type = ILoader::TYPE_INCLUDE;

    /**
     * @var $extension string
     */
    protected $extension = ILoader::EXT_PHP;

    /**
     * @var bool $return_loaded_file
     */
    protected $return_loaded_file = false;

    /**
     * Load a specific file with its filename
     *
     * @param string $filename
     * @param string $ext
     * @param null $type
     * @return mixed
     * @throws IFileNotExistsException
     */
    public function load(string $filename, string $ext = null, $type = null)
    {
        $filename = $this->addExtensionIfNotExist($filename, $ext, $this->extension, $this->allowed_extensions);
        $type = $this->_isValidType($type) ? $type : $this->type;

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
        $return = $this->return_loaded_file ? $return : $this;
        $this->returnLoadedFile(false);
        $this->setData([]);
        return $return;
    }

    /**
     * @param string $filename
     * @param string|null $ext
     * @return mixed
     * @throws IFileNotExistsException
     */
    public function load_include(string $filename, string $ext = null)
    {
        return $this->load($filename, $ext, ILoader::TYPE_INCLUDE);
    }

    /**
     * @param string $filename
     * @param string|null $ext
     * @return mixed
     * @throws IFileNotExistsException
     */
    public function load_include_once(string $filename, string $ext = null)
    {
        return $this->load($filename, $ext, ILoader::TYPE_INCLUDE_ONCE);
    }

    /**
     * @param string $filename
     * @param string|null $ext
     * @return mixed
     * @throws IFileNotExistsException
     */
    public function load_require(string $filename, string $ext = null)
    {
        return $this->load($filename, $ext, ILoader::TYPE_REQUIRE);
    }

    /**
     * @param string $filename
     * @param string|null $ext
     * @return mixed
     * @throws IFileNotExistsException
     */
    public function load_require_once(string $filename, string $ext = null)
    {
        return $this->load($filename, $ext, ILoader::TYPE_REQUIRE_ONCE);
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
     * @param int $type
     * @return static
     */
    public function setType($type = ILoader::TYPE_INCLUDE)
    {
        $this->type = $this->_isValidType($type) ? $type : ILoader::TYPE_INCLUDE;
        return $this;
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
     * Set default file extension
     *
     * @param string $ext
     * @return static
     */
    public function setExtension($ext = ILoader::EXT_PHP)
    {
        $ext = mb_strtolower((string)$ext);
        if (in_array($ext, $this->allowed_extensions)) {
            $this->extension = $ext;
        }
        return $this;
    }

    /**
     * Return loaded file or just load it
     *
     * @param bool $answer
     * @return static
     */
    public function returnLoadedFile($answer = false)
    {
        $this->return_loaded_file = (bool)$answer;
        return $this;
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