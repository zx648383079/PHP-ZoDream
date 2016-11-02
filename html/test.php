<?php
//define('DEBUG', true);
define('APP_DIR', dirname(dirname(__FILE__)));
require_once(APP_DIR.'/vendor/autoload.php');
function quickRandom($length = 6, $id = 0, $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZuvwxyzABCDlmnopqrstuvwxyzABCDEFGHIJKLMNOP') {
    $id = intval($id);
    $str = '';
    $len = 0;
    $max = strlen($pool);
    while ($id > 0) {
        $index = $id % $max;
        $str = $pool[$index].$str;
        $len ++;
        $id = floor($id / $max);
    }
    return substr($str.str_shuffle(str_repeat($pool, $length - $len)), 0, $length);
}
var_dump(quickRandom(6, 1600000000));
