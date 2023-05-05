<?php
namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Zodream\Infrastructure\Support\UserAgent;

final class UserAgentTest extends TestCase {

    public static function agentProvider(): array
    {
        return [
            ['Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Safari/537.36 Vivaldi/5.3.2679.68',
                'Linux', 'unknown', 'Vivaldi', '5.3.2679.68'],
            ['Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36 Edg/112.0.1722.68',
                'Windows', '10.0', 'Edge', '112.0.1722.68'],
            [
                'Mozilla/5.0 (Linux; U; Android 11; zh-cn; PCNM00 Build/RKQ1.200903.002) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/70.0.3538.80 Mobile Safari/537.36 HeyTapBrowser/40.7.26.1 Moos/1',
                'Android', '11', 'HeyTapBrowser', '40.7.26.1'
            ]
        ];
    }
    #[DataProvider('agentProvider')]
    public function testOs(string $agent, string $os, $osVersion, string $browser, string $browserVersion) {
        $data = UserAgent::os($agent);
        $this->assertEquals($data[0], $os);
        $this->assertEquals($data[1], $osVersion);
        $data = UserAgent::browser($agent);
        $this->assertEquals($data[0], $browser);
        $this->assertEquals($data[1], $browserVersion);
    }
}