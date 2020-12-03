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
use Sim\Crypt\Crypt as Crypt;
use Sim\Cart\Cart as Cart;
use \Sim\HitCounter\HitCounter as HitCounter;
use Sim\DBConnector;

if (!function_exists('container')) {
    function container(): Container
    {
        return Container::getInstance();
    }
}

if (!function_exists('connector')) {
    function connector(): DBConnector
    {
        return \container()->get(DBConnector::class);
    }
}

if (!function_exists('loader')) {
    function loader(): Loader
    {
        /**
         * @var \Sim\Traits\TraitLoader $loader
         */
        $loader = \container()->get(Loader::class);
        return $loader;
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

if (!function_exists('cryptographer')) {
    function cryptographer(): Crypt
    {
        if (!\container()->has('cryptographer/class')) {
            // crypt class
            \container()->set('cryptographer/class', function () {
                return new Crypt(\config()->get('security.main_key'),
                    \config()->get('security.assured_key'));
            });
        }

        return \container()->get('cryptographer/class');
    }
}

if (!function_exists('session')) {
    function session(): Session
    {
        if (!\container()->has(Session::class)) {
            // session class
            \container()->set(Session::class, function () {
                return new Session(\cryptographer());
            });
        }

        return \container()->get(Session::class);
    }
}

if (!function_exists('cookie')) {
    function cookie(): Cookie
    {
        if (!\container()->has(Cookie::class)) {
            // cookie class
            \container()->set(Cookie::class, function () {
                return new Cookie(\cryptographer());
            });
        }

        return \container()->get(Cookie::class);
    }
}

if (!function_exists('csrf')) {
    function csrf(): Csrf
    {
        /**
         * @var Csrf $csrf
         */
        $csrf = \container()->get(Csrf::class);
        $csrf->setExpiration(\config()->get('csrf.expiration') ?? 0);
        return $csrf;
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
        if (!\container()->has(Translate::class)) {
            // translator class
            \container()->set(Translate::class, function () {
                $translate = new Translate();
                $translateConfig = config()->get('i18n');
                if (!is_null($translateConfig)) {
                    /** @var array $translateConfig */
                    $translate->setTranslateDir($translateConfig['language_dir'] ?? '')
                        ->setLocale($translateConfig['language'] ?? '');

                    if ((bool)config()->get('i18n.is_rtl') === true) {
                        $translate->itIsRTL();
                    }
                }

                return $translate;
            });
        }

        return \container()->get(Translate::class);
    }
}

if (!function_exists('cart')) {
    function cart(): Cart
    {
        if (!\container()->has(Cart::class)) {
            // cart class
            \container()->set(Cart::class, function (Container $resolver) {
                $cookie = $resolver->get(Cookie::class);
                return new Cart(\connector()->getPDO(), $cookie, 0, \config()->get('cart.structure'));
            });
        }

        return \container()->get(Cart::class);
    }
}

if (!function_exists('hit_counter')) {
    function hit_counter(): HitCounter
    {
        if (!\container()->has(HitCounter::class)) {
            // hit counter class
            \container()->set(HitCounter::class, function () {
                return new HitCounter(\connector()->getPDO(), \config()->get('hit.structure'));
            });
        }

        return \container()->get(HitCounter::class);
    }
}

if (!function_exists('manifest_content')) {
    function manifest_content(): array
    {
        static $manifest = [];

        if (empty($manifest)) {
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
