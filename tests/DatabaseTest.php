<?php
namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Zodream\Database\Utils;

final class DatabaseTest extends TestCase {

    public static function wrapProvider(): array {
        return [
            [['a'], '`a`'],
            [['a', 'b'], '`b`.`a`'],
            [['`a`.`c` AS d', 'b'], '`b`.`c` AS d'],
            [['`a`.`c` d', 'b'], '`b`.`c` AS d'],
        ];
    }

    #[DataProvider('wrapProvider')]
    public function testWrapTable(array $input, string $res) {
        $this->assertEquals(Utils::wrapTable(...$input), $res);
    }

}