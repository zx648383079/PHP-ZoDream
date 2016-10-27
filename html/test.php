<?php
//define('DEBUG', true);
define('APP_DIR', dirname(dirname(__FILE__)));
require_once(APP_DIR.'/vendor/autoload.php');
$generate = new \Zodream\Domain\Generate\BlockGenerate();
$generate->space('Zodream')->className('aa')->addBlock('function view{action}($id) {
$model = new {model}();
$data  = $model->findById($id);
$this->show([
\'data\' => $data
]);
}');
var_dump(json_encode([
    '1' => 2,
    'a' => 'c',
    'd' => '3'
]));
die(var_dump((string)$generate));
