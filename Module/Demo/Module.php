<?php
namespace Module\Demo;

use Zodream\Route\Controller\Concerns\StaticAssetsRoute;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule {
    use StaticAssetsRoute;
}