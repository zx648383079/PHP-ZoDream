<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use Zodream\Infrastructure\Event\EventManger;

final class EventTest extends TestCase {

    public function testFilter() {
        $event = __FUNCTION__;
        $emitter = new EventManger();
        $emitter->listen($event, function ($val) {
           return $val - 1;
        });
        $this->assertEquals($emitter->filter($event, [2]), 1);
    }
}