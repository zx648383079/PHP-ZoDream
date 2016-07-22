<?php

class a {
    protected $a = 1;

    public function c() {
        return 1;
    }
}

var_dump(is_callable('a@c'));


