<?php
namespace Module\Blog\Domain;

use Zodream\Service\Factory;

class Weather {
    public static function getList() {
        $file = Factory::root()->childFile('data/languages/zh-cn/zodream.php');
        $res = include_once (string)$file;
        return empty($res) || !isset($res['weathers']) ? [] : $res['weathers'];
    }
}
