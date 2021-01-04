<?php
namespace Module\Blog\Domain;



class Weather {
    public static function getList() {
        $file = app_path()->childFile('data/languages/zh-cn/zodream.php');
        $res = include_once (string)$file;
        return empty($res) || !isset($res['weathers']) ? [] : $res['weathers'];
    }
}
