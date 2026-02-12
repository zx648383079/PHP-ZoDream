<?php
declare(strict_types=1);
namespace Service\Ueditor;

use Service\Controller as BaseController;

abstract class Controller extends BaseController {
	
	public function rules() {
		return [
			'*' => '@'
		];
	}
}