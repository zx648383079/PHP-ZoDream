<?php
declare(strict_types=1);
namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Zodream\Route\Rewrite\RewriteEncoder;
use Zodream\Infrastructure\Contracts\UrlGenerator;
use Zodream\Service\Application;

final class RewriteTest extends TestCase {

    public static function urlProvider(): array
    {
        return [
            ['', [], '', []],
            ['/', [], '', []],
            ['blog', [], 'blog.html', []],
            ['blog', ['id' => '1'], 'blog/id/1.html', []],
            ['blog', ['tag' => 'name'], 'blog/0_0/tag/name.html', []],
            ['blog', ['tag' => 'name', 'url' => 'u/a'], 'blog/0_0/tag/name.html', ['url' => 'u/a']],

        ];
    }

    public static function otherProvider(): array
    {
        return [
            ['./index', ['tag' => 'name', 'url' => 'u/a'], 'http://127.0.0.1/index/0_0/tag/name.html?url=u%2Fa'],
            ['https://zodream.cn/index', ['tag' => 'name', 'url' => 'u/a'], 'https://zodream.cn/index?tag=name&url=u%2Fa'],
        ];
    }

    public function encoder(): RewriteEncoder {
        $setting = config();
        $setting->set('route.rewrite', '.html');
        $setting->set('route.encoders', [
            'Zodream\Route\Rewrite\RewriteEncoder'
        ]);
        return Application::getInstance()->make(RewriteEncoder::class);
    }

    #[DataProvider('urlProvider')]
    public function testEn(string $path, array $data, string $url, array $params) {
        list($res, $val) = $this->encoder()->enRewrite($path, $data);
        $this->assertSame($res, $url);
        $this->assertSame($val, $params);
    }

    #[DataProvider('urlProvider')]
    public function testDe(string $path, array $data, string $url, array $params) {
        list($res, $val) = $this->encoder()->deRewrite($url);
        $this->assertSame($res, rtrim($path, '/'));
        if (isset($data['url'])) {
            unset($data['url']);
        }
        $this->assertSame($val, $data);
    }

    #[DataProvider('otherProvider')]
    public function testOther(string $path, array $data, string $url) {
        $this->encoder();
        $builder = Application::getInstance()->make(UrlGenerator::class);
        $res = $builder->to($path, $data);
        $this->assertSame($res, $url);
    }
}