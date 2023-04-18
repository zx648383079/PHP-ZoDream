<?php
namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Zodream\Route\Rewrite\RewriteEncoder;
use Zodream\Service\Application;

final class RewriteTest extends TestCase {

    public static function urlProvider(): array
    {
        return [
            ['', [], '', []],
            ['/', [], '', []],
            ['blog', [], 'blog.html', []],
            ['blog', ['id' => '1'], 'blog/id/1.html', []],
            ['blog', ['tag' => 'name'], 'blog/0/tag/name.html', []],
            ['blog', ['tag' => 'name', 'url' => 'u/a'], 'blog/0/tag/name.html', ['url' => 'u/a']],
        ];
    }

    public function encoder(): RewriteEncoder {
        config()->set('route.rewrite', '.html');
        return Application::getInstance()->make(RewriteEncoder::class);
    }

    #[DataProvider('urlProvider')]
    public function testEn($path, array $data, string $url, array $params) {
        list($res, $val) = $this->encoder()->enRewrite($path, $data);
        $this->assertSame($res, $url);
        $this->assertSame($val, $params);
    }

    #[DataProvider('urlProvider')]
    public function testDe($path, array $data, string $url, array $params) {
        list($res, $val) = $this->encoder()->deRewrite($url);
        $this->assertSame($res, rtrim($path, '/'));
        if (isset($data['url'])) {
            unset($data['url']);
        }
        $this->assertSame($val, $data);
    }
}