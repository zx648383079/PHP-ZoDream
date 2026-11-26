<?php
declare(strict_types=1);
namespace Tests;

use Zodream\Database\Query\Builder;
use PHPUnit\Framework\TestCase;

final class QueryTest extends TestCase {

    public function testBuild() {
        $builder = new Builder();
        $res = $builder->select('id')->from('users')->where('a', 1)
            ->orderBy('id', SORT_DESC)->thenBy('time', SORT_ASC)
            ->skip(1)->take(10)
            ->getSQL();
        $this->assertEquals($res, 'SELECT id FROM `users` WHERE a = ? ORDER BY id DESC,time ASC LIMIT 10 OFFSET 1');
    }

}