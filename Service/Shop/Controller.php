<?php
namespace Service\Shop;

use Zodream\Route\Controller\Controller as BaseController;

abstract class Controller extends BaseController {
	public function prepare() {
        $this->send([
            'cartCount' => 0,
            'cats' => [],
            'toMenu' => [],
            'helpMap' => [],
            'links' => [],
            'contact' => []
        ]);
	}
}