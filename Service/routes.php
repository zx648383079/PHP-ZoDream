<?php
use Zodream\Infrastructure\Contracts\Router;
/** @var Router $router */
$router->get('gggggg', 'HomeController@aboutAction');
$router->group([
    'module' => 'Module\Blog\Module',
    'namespace' => '\Module\Blog\Service'
], function ($router) {
   $router->get('hhh', 'HomeController@indexAction');
});