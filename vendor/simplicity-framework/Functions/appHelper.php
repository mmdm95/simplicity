<?php

use Sim\Captcha\CaptchaFactory;
use Sim\Captcha\Interfaces\ICaptchaLanguage;
use Sim\ConfigManager\ConfigManager;
use Sim\Container\Container;
use Sim\Cookie\Cookie as Cookie;
use Sim\Csrf\Csrf;
use Sim\Event\Emitter as Emitter;
use Sim\I18n\Translate;
use Sim\Loader\Loader as Loader;
use Sim\PathManager\PathManager;
use Sim\Session\Session as Session;

if (!function_exists('container')) {
    function container(): Container
    {
        return Container::getInstance();
    }
}

if (!function_exists('loader')) {
    function loader(): Loader
    {
        return \container()->get(Loader::class);
    }
}

if (!function_exists('path')) {
    function path(): PathManager
    {
        return \container()->get(PathManager::class);
    }
}

if (!function_exists('config')) {
    function config(): ConfigManager
    {
        return \container()->get(ConfigManager::class);
    }
}

if (!function_exists('emitter')) {
    function emitter(): Emitter
    {
        return \container()->get(Emitter::class);
    }
}

if (!function_exists('session')) {
    function session(): Session
    {
        return \container()->get(Session::class);
    }
}

if (!function_exists('cookie')) {
    function cookie(): Cookie
    {
        return \container()->get(Cookie::class);
    }
}

if (!function_exists('csrf')) {
    function csrf(): Csrf
    {
        return \container()->get(Csrf::class);
    }
}

if (!function_exists('captcha')) {
    function captcha(int $type = CaptchaFactory::CAPTCHA, ICaptchaLanguage $language = null)
    {
        return CaptchaFactory::instance($type, $language);
    }
}

if (!function_exists('translate')) {
    function translate(): Translate
    {
        return container()->get(Translate::class);
    }
}

if (!function_exists('manifest_content')) {
    function manifest_content(): array
    {
        static $manifest = [];

        if(empty($manifest)) {
            // get manifest path
            $manifestPath = get_path('manifest', '', false);

            // read manifest if exists
            $data = file_get_contents($manifestPath);
            if (false !== $data) {
                $manifest = json_decode($data, true);
            }
        }

        return $manifest;
    }
}
