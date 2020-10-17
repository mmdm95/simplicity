<?php

namespace App\Logic;

use App\Logic\Controllers\ResourceController;
use App\Logic\Handlers\CustomExceptionHandler;
use App\Logic\Middlewares\AdminAuthMiddleware;
use Pecee\SimpleRouter\SimpleRouter as Router;
use Sim\Interfaces\IInitialize;

class Route implements IInitialize
{
    /**
     * Route definitions
     * @throws \Exception
     */
    public function init()
    {
        $this->setDependencyInjection();
        $this->setDefaultNamespace();
        $this->webRoutes();
    }

    /**
     * @throws \Exception
     */
    protected function setDependencyInjection()
    {
        // Development code for container
        // Create our new php-di container
        $container = (new \DI\ContainerBuilder())
            ->useAutowiring(true)
            ->build();

        // Production code for container
//        // Cache directory
//        $cacheDir = cache_path('simple-router');
//
//        // Create our new php-di container
//        $container = (new \DI\ContainerBuilder())
//            ->enableCompilation($cacheDir)
//            ->writeProxiesToFile(true, $cacheDir . '/proxies')
//            ->useAutowiring(true)
//            ->build();

        // Add our container to simple-router and enable dependency injection
        Router::enableDependencyInjection($container);
    }

    /**
     * Default namespace for route
     */
    protected function setDefaultNamespace()
    {
        /**
         * The default namespace for route-callbacks, so we don't have to specify it each time.
         * Can be overwritten by using the namespace config option on your routes.
         */
        Router::setDefaultNamespace('\App\Logic\Controllers');
    }

    /**
     * Routes of web part
     */
    protected function webRoutes()
    {
        //==========================
        // not found route in admin
        //==========================
        Router::get('/admin/page/404', 'PageController@adminNotFound')->name(NOT_FOUND_ADMIN);

        //==========================
        // not found route
        //==========================
        Router::get('/page/404', 'PageController@notFound')->name(NOT_FOUND);

        //==========================
        // server error route
        //==========================
        Router::get('/page/500', 'PageController@serverError')->name(SERVER_ERROR);

        Router::group(['exceptionHandler' => CustomExceptionHandler::class], function () {
            //==========================
            // admin routes
            //==========================
            Router::group(['prefix' => '/admin', 'middleware' => AdminAuthMiddleware::class], function () {
                Router::get('/', function () {
                    if (request()->authenticated) {
                        return 'hello world!';
                    } else {
                        return 'Access denied!';
                    }
                });
            });

            //==========================
            // other routes
            //==========================
            Router::get('/', function () {
                return 'hi mmdm';
            });

            Router::get('/home/{id?}', 'HomeController@index');
            Router::get('/answers/{id}', 'HomeController@show', ['where' => ['id' => '[0-9]+']]);

            /**
             * Restful resource (see IResourceController interface for available methods)
             */
            Router::resource('/rest', ResourceController::class);
        });
    }
}
