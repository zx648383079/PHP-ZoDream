<?php
namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Zodream\Route\ModuleRoute;

final class RouteTest extends TestCase {

    public static function pathProvider(): array
    {
        return [
            ['blog', 'blog/aaa'],
        ];
    }

    #[DataProvider('pathProvider')]
    public function testMatch(string $module, string $path) {
        $router = new ModuleRoute();
        $this->assertTrue($router->isMatch($path, $module));
    }
}