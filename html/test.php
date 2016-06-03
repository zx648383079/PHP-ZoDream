<?php
require_once('/../vendor/autoload.php');
$a = \Zodream\Infrastructure\ObjectExpand\YamlExpand::decodeFile(__DIR__.'/test.yaml');
var_dump($a);