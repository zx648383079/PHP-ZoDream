<?php
namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Zodream\Infrastructure\Support\RouteHelper;
use Zodream\Route\ModuleRoute;

final class RouteTest extends TestCase {

    public static function pathProvider(): array
    {
        return [
            ['blog', 'blog/aaa', 'index.php', 'blog/aaa'],
            ['blog', '/blog/aaa', 'api/index.php', 'api/blog/aaa'],
            ['blog', '/blog/aaa', 'api/install.php', 'api/install.php/blog/aaa'],
        ];
    }

    public static function actionProvider(): array
    {
        return [
            ['Service\Home\ToController@index', '/to'],
            ['Module\Auth\Service\Api\BatchController@index', '/auth/api/batch'],
            ['Module\Auth\Service\Api\HomeController@index', '/auth/api'],
        ];
    }

    #[DataProvider('pathProvider')]
    public function testMatch(string $module, string $pathInfo, string $script, $path) {
        $this->assertTrue(RouteHelper::startWithRoute($pathInfo, $module));
        $this->assertEquals(RouteHelper::getPathInfo($path, $script), $pathInfo);
    }

    #[DataProvider('actionProvider')]
    public function testGenerate(string $module, string $path) {
        $route = new ModuleRoute();
        $args = $route->formatAction($module);
        $this->assertEquals($route->toPath($args['module'] ?? '',
            $args['controller'] ?? '', $args['action'] ?? ''), $path);
    }
}