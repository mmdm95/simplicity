<?php

namespace App\Logic;

use Sim\Auth\Interfaces\IAuth;
use Sim\Interfaces\IInitialize;
use Sim\Container\Container as Resolver;
use Sim\Auth\DBAuth as Auth;

class Container implements IInitialize
{
    /**
     * Container initializer
     */
    public function init()
    {
        // home authentication class
        \container()->set('auth_home', function () {
            $authConfig = \config()->get('auth');

            $auth = new Auth(\connector()->getPDO(), 'home', [
                'main' => \config()->get('env.APP_MAIN_KEY'),
                'assured' => \config()->get('env.APP_ASSURED_KEY'),
            ], PASSWORD_BCRYPT, IAuth::STORAGE_DB, $authConfig['structure']);

            $auth->setExpiration($authConfig['expire_time'])->setSuspendTime($authConfig['suspend_time']);

            return $auth;
        });

        // admin authentication class
        \container()->set('auth_admin', function () {
            $authConfig = \config()->get('auth');

            $auth = new Auth(\connector()->getPDO(), 'admin', [
                'main' => \config()->get('env.APP_MAIN_KEY'),
                'assured' => \config()->get('env.APP_ASSURED_KEY'),
            ], PASSWORD_BCRYPT, IAuth::STORAGE_DB, \config()->get('auth.structure'));

            $auth->setExpiration($authConfig['expire_time'])->setSuspendTime($authConfig['suspend_time']);

            return $auth;
        });
    }
}