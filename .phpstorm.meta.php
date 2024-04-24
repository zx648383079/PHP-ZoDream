<?php
namespace PHPSTORM_META {


    override(app(0), map([
        '' => '@',
        'db' => \Zodream\Database\Command::class,
        'platform' => \Module\Auth\Domain\IAuthPlatform::class,
        'request' => \Zodream\Infrastructure\Contracts\Http\Input::class,
        'debugger' => \Zodream\Debugger\Debugger::class,
        'Zodream\Route\ModuleRoute' => \Zodream\Route\ModuleRoute::class,
        'Zodream\Infrastructure\Contracts\HttpContext' => \Zodream\Infrastructure\Contracts\HttpContext::class,
        'Module\CMS\Domain\Scene\SceneInterface' => \Module\CMS\Domain\Scene\SceneInterface::class
    ]));

    override(url(0), map([
        '' => \Zodream\Route\UrlGenerator::class,
    ]));
}