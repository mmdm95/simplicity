<?php

namespace Sim\Core;

use Sim\Interfaces\ConfigManager\IConfig;
use Sim\Interfaces\IInitialize;
use Sim\Interfaces\Loader\ILoader;
use Sim\Logger\ILogger;
use Sim\Traits\TraitExtension;
use Sim\Traits\TraitGeneral;

class ErrorHandler implements IInitialize
{
    use TraitGeneral;
    use TraitExtension {
        addExtensionIfNotExist as private;
    }

    /**
     * @var $mode
     */
    protected $mode;

    /**
     * @var string $err_500
     */
    protected $err_500 = '500.php';

    /**
     * @var string $err_any
     */
    protected $err_any = 'any.php';

    /**
     * @var bool $show_native_php_error
     */
    private $show_native_php_error = false;
    //    private $show_native_php_error = true;

    /**
     * @var IConfig $config
     */
    protected $config;

    /**
     * @var ILoader $loader
     */
    protected $loader;

    /**
     * @var ILogger $logger
     */
    protected $logger;

    public function __construct(IConfig $config, ILoader $loader, ILogger $logger)
    {
        $this->config = $config;
        $this->loader = $loader;
        $this->logger = $logger;
    }

    /**
     * Pass error handler methods insteadof current error handler
     *
     */
    public function init()
    {
        // Set current application mode
        $this->mode = $this->config->get('main.mode') ?? MODE_PRODUCTION;
        // Set current error handling mode
        $this->show_native_php_error = $this->config->get('main.show_native_errors') ?? false;
        //-----
        $this->setConstants();
        //-----
        if (!$this->show_native_php_error) {
            register_shutdown_function([$this, 'shut']);
            set_error_handler([$this, 'handler']);
            set_exception_handler([$this, 'logException']);
        }
    }

    /**
     * Error handler constants
     */
    protected function setConstants()
    {
        if ($this->show_native_php_error) {
            error_reporting(E_ALL);
            @ini_set("display_errors", 1);
        } else {
//            error_reporting(0);
            @ini_set("display_errors", 0);
        }

        //Custom error handling constants
        if (MODE_DEVELOPMENT == $this->mode) {
            defined('DISPLAY_ERRORS') OR define('DISPLAY_ERRORS', TRUE);
            defined('ERROR_REPORTING') OR define('ERROR_REPORTING', E_ALL | E_STRICT);
            defined('LOG_ERRORS') OR define('LOG_ERRORS', TRUE);
        } else { //if (MODE_PRODUCTION == $this->mode) {
            defined('DISPLAY_ERRORS') OR define('DISPLAY_ERRORS', FALSE);
            defined('ERROR_REPORTING') OR define('ERROR_REPORTING', ~E_ALL);
            defined('LOG_ERRORS') OR define('LOG_ERRORS', FALSE);
        }
    }

    /**
     * Error shutdown function
     */
    public function shut()
    {
        $error = error_get_last();
        if ($error && ($error['type'] & E_FATAL)) {
            $this->handler($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }

    /**
     * Error handler function
     *
     * @param $errno
     * @param $errStr
     * @param $errFile
     * @param $errLine
     */
    public function handler($errno, $errStr, $errFile, $errLine)
    {
        if (0 == error_reporting()) {
            return;
        }

        $typeStr = 'UNDEFINED';
        switch ($errno) {
            case E_ERROR: // 1 //
                $typeStr = 'E_ERROR';
                break;
            case E_WARNING: // 2 //
                $typeStr = 'E_WARNING';
                break;
            case E_PARSE: // 4 //
                $typeStr = 'E_PARSE';
                break;
            case E_NOTICE: // 8 //
                $typeStr = 'E_NOTICE';
                break;
            case E_CORE_ERROR: // 16 //
                $typeStr = 'E_CORE_ERROR';
                break;
            case E_CORE_WARNING: // 32 //
                $typeStr = 'E_CORE_WARNING';
                break;
            case E_COMPILE_ERROR: // 64 //
                $typeStr = 'E_COMPILE_ERROR';
                break;
            case E_COMPILE_WARNING: // 128 //
                $typeStr = 'E_COMPILE_WARNING';
                break;
            case E_USER_ERROR: // 256 //
                $typeStr = 'E_USER_ERROR';
                break;
            case E_USER_WARNING: // 512 //
                $typeStr = 'E_USER_WARNING';
                break;
            case E_USER_NOTICE: // 1024 //
                $typeStr = 'E_USER_NOTICE';
                break;
            case E_STRICT: // 2048 //
                $typeStr = 'E_STRICT';
                break;
            case E_RECOVERABLE_ERROR: // 4096 //
                $typeStr = 'E_RECOVERABLE_ERROR';
                break;
            case E_DEPRECATED: // 8192 //
                $typeStr = 'E_DEPRECATED';
                break;
            case E_USER_DEPRECATED: // 16384 //
                $typeStr = 'E_USER_DEPRECATED';
                break;
        }

        if (($errno & E_NOTICE) && (0 == ($this->mode ^ MODE_PRODUCTION))) return;

        $message = '<b>' . $typeStr . ': </b>' . $errStr . ' in <b>' . $errFile . '</b> on line <b>' . $errLine . '</b><br/>';

        // Set error in data to pass it to page(s)
        $Exceptions_detail = [
            'type' => $errno,
            'typeStr' => $typeStr,
            'message' => $errStr,
            'file' => $errFile,
            'line' => $errLine
        ];

        if (($errno & E_FATAL) && (0 == ($this->mode ^ MODE_PRODUCTION))) {
            $this->loadInternalServerErrorFile($message, $Exceptions_detail, $this->err_500);
            exit();
        }

        if (!($errno & ERROR_REPORTING))
//        $H->load->view('errors/Err_AnyErr');
            return;
        if (DISPLAY_ERRORS) {
            if ($errno == E_ERROR) {
                printf('%s', $message);
            } else {
                $this->loadInternalServerErrorFile($message, $Exceptions_detail, $this->err_any);
            }
        }
        //Logging error on php file error log...
        if (LOG_ERRORS)
            error_log(strip_tags($message), 0);
//    die();
    }

    /**
     * Uncaught exception handler.
     *
     * @see https://www.php.net/manual/en/function.set-error-handler.php#112291
     * @param \Throwable $e
     */
    public function logException(\Throwable $e)
    {
        if (DISPLAY_ERRORS) {
            print "<div style='text-align: center;'>";
            print "<h2 style='color: rgb(190, 50, 50); direction: ltr;'>Error occurred:</h2>";
            print "<table style='max-width: 800px; display: inline-block;'><tbody>";
            print "<tr style='background-color:rgb(230,230,230);'><th style='width: 80px;'>Type</th><td style='padding: 7px;'>" . get_class($e) . "</td></tr>";
            print "<tr style='background-color:rgb(240,240,240);'><th>Message</th><td style='padding: 7px;'>{$e->getMessage()}</td></tr>";
            print "<tr style='background-color:rgb(230,230,230);'><th>File</th><td style='padding: 7px;'>{$e->getFile()}</td></tr>";
            print "<tr style='background-color:rgb(240,240,240);'><th>Line</th><td style='padding: 7px;'>{$e->getLine()}</td></tr>";
            print "<tr style='background-color:rgb(230,230,230);'><th>Trace</th><td style='padding: 7px;'>{$e->getTraceAsString()}</td></tr>";
            print "</table></tbody></div>";
        } else {
            $message = "Message: {$e->getMessage()}; File: {$e->getFile()}; Line: {$e->getLine()};";
            // Log the error
//            file_put_contents(log_path("exceptions.log"), $message . PHP_EOL, FILE_APPEND);
            $this->logger->log($message, get_class($e));

            // Load 500 error file
            $Exceptions_detail = $this->messageFormat(get_class($e), get_class($e), $e->getMessage(), $e->getFile(), $e->getLine());
            $this->loadInternalServerErrorFile($message, $Exceptions_detail, $this->err_500);
        }

        exit();
    }

    /**
     * @param $message
     * @param $Exceptions_detail
     * @param $page
     */
    protected function loadInternalServerErrorFile($message, $Exceptions_detail, $page)
    {
        header('HTTP/1.1 500 Internal Server Error');
        $content = $this->loader->setData([
            'Exceptions_message' => $message,
            'Exceptions_detail' => $Exceptions_detail
        ])->getContent((error_path($page, false)));
        echo $content;
    }

    /**
     * @param $type
     * @param $typeStr
     * @param $msg
     * @param $file
     * @param $line
     * @return array
     */
    protected function messageFormat($type, $typeStr, $msg, $file, $line)
    {
        return [
            'type' => $type,
            'typeStr' => $typeStr,
            'message' => $msg,
            'file' => $file,
            'line' => $line
        ];
    }
}