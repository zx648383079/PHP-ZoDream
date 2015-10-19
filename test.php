<?php 
function expl($name)
{
	var_dump(explode('/',$name,2));
}

expl('hhh');
expl('aa/aa');
expl('bb/nn/cc/dd');

function memo($arr,$arr2)
{
	var_dump(array_merge($arr,$arr2));
}


$data = array(
	'a' => 'b',
	'b' => 'c',
	'c' => 'd'
);

unset($data['a']);
var_dump($data);

echo -1;

var_dump($_SERVER['argv']);
var_dump($_SERVER);
//"SESSIONNAME"