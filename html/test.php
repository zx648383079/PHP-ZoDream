<?php
function &aa() {
    static $a = 0;
    $a ++;
    return $a;
}

$b = &aa();
$c = aa();
var_dump($b);
var_dump($c);