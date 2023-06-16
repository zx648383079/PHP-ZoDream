<?php
namespace Tests;

use Module\Auth\Domain\Helpers;
use PHPUnit\Framework\TestCase;

final class HelperTest extends TestCase {

    public function testHide() {
        $this->assertEquals(Helpers::hideIp('127.0.0.1'), '127.*.*.1');
    }

}