<?php
namespace Service\Ueditor;

use Service\Controller as BaseController;

abstract class Controller extends BaseController {
	
	public function rules() {
		return [
			'*' => '@'
		];
	}
}