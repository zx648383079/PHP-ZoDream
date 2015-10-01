<?php 
function expl($name)
{
	var_dump(explode('/',$name,2));
}

expl('hhh');
expl('aa/aa');
expl('bb/nn/cc/dd');