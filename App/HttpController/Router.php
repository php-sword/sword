<?php
namespace App\HttpController;

use EasySwoole\Http\AbstractInterface\AbstractRouter;
use FastRoute\RouteCollector;

/**
 * Http路由注册
 */
class Router extends AbstractRouter
{
    function initialize(RouteCollector $routeCollector)
    {
        // $routeCollector->get('/user', '/user/index');
    }
}
