<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use Zodream\Disk\FileSystem;

final class DiskTest extends TestCase {

    public function testCombine() {
        $file = FileSystem::combine('', '..', '..', 'user.php');
        $this->assertEquals($file, '/user.php');
    }

    public function testCombine2() {
        $file = FileSystem::combine('/', '..', '..', 'user.php');
        $this->assertEquals($file, '/user.php');
    }
    public function testCombine3() {
        $file = FileSystem::combine('/u/b', '..', '..', 'user.php');
        $this->assertEquals($file, '/user.php');
    }

    public function testCombine4() {
        $file = FileSystem::combine('/u', '..', '..', 'user.php');
        $this->assertEquals($file, '/user.php');
    }

    public function testCombine5() {
        $file = FileSystem::combine('/u', '...', 'user.php');
        $this->assertEquals($file, '/u/.../user.php');
    }
}