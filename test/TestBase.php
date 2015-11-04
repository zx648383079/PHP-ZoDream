<?php
namespace Test;

use App\Lib\Route;

class TestBase extends \PHPUnit_Framework_TestCase {
    public function testRouter() {
        Route::load();
    }
    
    public function testConfig() {
        $this->assertEquals(-1, \App::config('app.url'));
    }
}