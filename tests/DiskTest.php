<?php
namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Zodream\Disk\FileSystem;
use Zodream\Disk\StreamFinder;

final class DiskTest extends TestCase {

    public static function combineProvider(): array {
        return [
            [['', '..', '..', 'user.php'], '/user.php'],
            [['/', '..', '..', 'user.php'], '/user.php'],
            [['/u/b', '..', '..', 'user.php'], '/user.php'],
            [['/u', '..', '..', 'user.php'], '/user.php'],
            [['/u', '...', 'user.php'], '/u/.../user.php'],
            [['/u/b/c', '../', './', '../user.php'], '/u/user.php'],
            [['d:/b/c', '../', '../', '../user.php'], 'd:/user.php'],
        ];
    }

    public static function filterProvider(): array {
        return [
            ['/v/.././../a.php', '/v/a.php'],
            ['', '/'],
            ['d:/a/../.././a.php', 'd:/a/a.php'],
            ['d:', 'd:/'],
        ];
    }

    public static function finderProvider(): array {
        return [
            [__FILE__, true],
            [__DIR__.'/../composer.json', false],
        ];
    }

    #[DataProvider('combineProvider')]
    public function testCombine(array $items, string $path) {
        $this->assertEquals(FileSystem::combine(...$items), $path);
    }

    #[DataProvider('filterProvider')]
    public function testFilter(string $input, string $path) {
        $this->assertEquals(FileSystem::filterPath($input, true), $path);
    }

    #[DataProvider('finderProvider')]
    public function testFinder(string $file, bool $success) {
        $finder = new StreamFinder([
            ['<%', '%>'],
            '<?php',
            ['<?=', '?>']
        ]);
        $this->assertEquals($finder->matchFile($file), $success);
    }
}