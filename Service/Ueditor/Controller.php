<?php
namespace Service\Ueditor;

use Zodream\Route\Controller\Controller as BaseController;

abstract class Controller extends BaseController {
	
	public function rules() {
		return [
			'*' => '@'
		];
	}
}