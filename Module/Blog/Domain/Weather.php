<?php
declare(strict_types=1);
namespace Module\Blog\Domain;

class Weather {
    public static function getList() {
        $res = trans('weathers');
        return empty($res) ? [] : $res;
    }
}
