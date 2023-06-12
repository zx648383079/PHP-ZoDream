<?php
namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Zodream\Infrastructure\Support\RouteHelper;

final class RouteTest extends TestCase {

    public static function pathProvider(): array
    {
        return [
            ['blog', 'blog/aaa', 'index.php', 'blog/aaa'],
            ['blog', '/blog/aaa', 'api/index.php', 'api/blog/aaa'],
            ['blog', '/blog/aaa', 'api/install.php', 'api/install.php/blog/aaa'],
        ];
    }

    #[DataProvider('pathProvider')]
    public function testMatch(string $module, string $pathInfo, string $script, $path) {
        $this->assertTrue(RouteHelper::startWithRoute($pathInfo, $module));
        $this->assertEquals(RouteHelper::getPathInfo($path, $script), $pathInfo);
    }
}