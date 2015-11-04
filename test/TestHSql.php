<?php
namespace Test;

use App\Lib\Helper\HSql;
//phpunit --bootstrap vendor/autoload.php test/TestHSql

class TestBase extends \PHPUnit_Framework_TestCase {
    public function testRouter() {
        $sql = new HSql();
        echo $sql->getSQL(array(
            'select' => '*',
            'from'   => 'user',
            'left'   => array(
                'table',
                'aa.a = bb.a'
            ),
            'right'  => array(
                'cc',
                'aa.v = cc.a'
            ),
            'limit'  => '1,20',
            'order'  => 'date',
            'group'  => 'group',
            'left`'  => array(
                '(',
                'select' => '*',
                'from'   => 'user',
                'left'   => array(
                    'table',
                    'aa.a = bb.a'
                ),
                'right'  => array(
                    'cc',
                    'aa.v = cc.a'
                ),
                'limit'  => '1,20',
                'order'  => 'date',
                'group'  => 'group )',
            )
        )); 
    }
}