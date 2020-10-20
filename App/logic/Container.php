<?php

namespace App\Logic;

use Sim\Auth\Interfaces\IAuth;
use Sim\Interfaces\IInitialize;
use Sim\Container\Container as Resolver;
use Sim\Auth\DBAuth as Auth;
use Sim\Auth\APIAuth as APIAuth;

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

            $auth->setExpiration($authConfig['namespaces']['home']['expire_time'])
                ->setSuspendTime($authConfig['namespaces']['home']['suspend_time']);

            return $auth;
        });

        // admin authentication class
        \container()->set('auth_admin', function () {
            $authConfig = \config()->get('auth');

            $auth = new Auth(\connector()->getPDO(), 'admin', [
                'main' => \config()->get('env.APP_MAIN_KEY'),
                'assured' => \config()->get('env.APP_ASSURED_KEY'),
            ], PASSWORD_BCRYPT, IAuth::STORAGE_DB, $authConfig['structure']);

            $auth->setExpiration($authConfig['namespaces']['admin']['expire_time'])
                ->setSuspendTime($authConfig['namespaces']['admin']['suspend_time']);

            return $auth;
        });

        // api authentication class
        \container()->set('auth_api', function () {
            return new APIAuth(\connector()->getPDO(), \config()->get('auth.structure'));
        });
    }
}