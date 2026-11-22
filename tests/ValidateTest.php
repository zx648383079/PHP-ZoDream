<?php
namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Zodream\Validate\Validator;

final class ValidateTest extends TestCase {

    public static function ruleProvider(): array {
        return [
            [1, 'bool', true],
            ['12@12.c', 'email', true],
            ['c', 'in:a,b,c,d', true],
            [99, 'int:4-100', true],
            ['127.0.0.1', 'ip', true],
            ['[]', 'json', true],
            ['hh', 'length:2,9', true],
            [10, 'max:9', false],
            [10, 'min:9', true],
            [null, 'nullable', true],
            ['9.0', 'numeric', true],
            [16901231234, 'phone', false],
            [13901231234, 'phone', true],
            ['a6', 'regex:/^[\w]+$/', true],
            ['', 'required', false],
            ['a', 'string:4,9', false],
            ['2019-09-01', 'time', true],
            ['a', 'url', false],
        ];
    }

    public function testFilter() {
        $data = Validator::filter([
            'a' => '1',
            'b' => null,
            'c' => 'aa'
        ], [
            'a' => 'int:1,100',
            'c' => 'string:1,9'
        ]);
        $this->assertEquals(count($data), 2);
        $this->assertEquals($data['a'], 1);
        $this->assertEquals($data['c'], 'aa');
    }

    #[DataProvider('ruleProvider')]
    public function testRule(mixed $input, string $rule, bool $output) {
        $args = explode(':', $rule);
        $ruleName = $args[0];
        $args = count($args) > 1 ? explode(',', $args[1]) : [];
        $this->assertEquals(Validator::buildRule($ruleName, $args)->validate($input), $output);
    }
}