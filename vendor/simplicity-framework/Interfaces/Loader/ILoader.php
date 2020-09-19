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
    public function load(string $filename, string $ext = null, $type = null);

    /**
     * @param string $filename
     * @param string|null $ext
     * @return mixed
     */
    public function load_include(string $filename, string $ext = null);

    /**
     * @param string $filename
     * @param string|null $ext
     * @return mixed
     */
    public function load_include_once(string $filename, string $ext = null);

    /**
     * @param string $filename
     * @param string|null $ext
     * @return mixed
     */
    public function load_require(string $filename, string $ext = null);

    /**
     * @param string $filename
     * @param string|null $ext
     * @return mixed
     */
    public function load_require_once(string $filename, string $ext = null);

    /**
     * Get content of a file as string
     *
     * @param string $filename
     * @param string|null $ext
     * @return string
     */
    public function getContent(string $filename, string $ext = null);

    /**
     * @param int $type
     * @return static
     */
    public function setType($type = self::TYPE_REQUIRE_ONCE);

    /**
     * Set data to pass included file.
     * Note: Use this function before load() method
     *
     * @param array $data
     * @return static
     */
    public function setData(array $data = []);

    /**
     * Set default file extension
     *
     * @param string $ext
     * @return static
     */
    public function setExtension($ext = self::EXT_PHP);

    /**
     * Return loaded file or just load it
     *
     * @param bool $answer
     * @return static
     */
    public function returnLoadedFile($answer = false);
}