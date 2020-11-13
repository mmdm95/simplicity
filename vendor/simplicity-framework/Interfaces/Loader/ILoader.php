<?php

namespace Sim\Interfaces\Loader;

interface ILoader
{
    /**
     * Type constants
     */
    const TYPE_INCLUDE = 0x01;
    const TYPE_INCLUDE_ONCE = 0x02;
    const TYPE_REQUIRE = 0x04;
    const TYPE_REQUIRE_ONCE = 0x08;

    /**
     * Allowed extension constants
     */
    const EXT_PHP = 'php';
    const EXT_PHTML = 'phtml';
    const EXT_HTML = 'html';

    /**
     * Load a specific file with its filename
     *
     * @param string $filename
     * @param string|null $ext
     * @param null $type
     * @return mixed
     */
    public function load(string $filename, ?string $ext = null, int $type = ILoader::TYPE_INCLUDE);

    /**
     * Load a specific file with its filename and return data of it.
     * Mostly use to get an array or something
     *
     * @param string $filename
     * @param string|null $ext
     * @param int $type
     * @return mixed
     */
    public function loadNReturn(string $filename, string $ext = null, int $type = ILoader::TYPE_INCLUDE);

    /**
     * @param string $filename
     * @param string|null $ext
     * @param bool $return_result
     * @return mixed
     */
    public function load_include(string $filename, string $ext = null, bool $return_result = false);

    /**
     * @param string $filename
     * @param string|null $ext
     * @param bool $return_result
     * @return mixed
     */
    public function load_include_once(string $filename, string $ext = null, bool $return_result = false);

    /**
     * @param string $filename
     * @param string|null $ext
     * @param bool $return_result
     * @return mixed
     */
    public function load_require(string $filename, string $ext = null, bool $return_result = false);

    /**
     * @param string $filename
     * @param string|null $ext
     * @param bool $return_result
     * @return mixed
     */
    public function load_require_once(string $filename, string $ext = null, bool $return_result = false);

    /**
     * Get content of a file as string
     *
     * @param string $filename
     * @param string|null $ext
     * @return string
     */
    public function getContent(string $filename, string $ext = null);

    /**
     * Set data to pass included file.
     * Note: Use this function before load() method
     *
     * @param array $data
     * @return static
     */
    public function setData(array $data = []);
}