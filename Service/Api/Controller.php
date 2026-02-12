<?php
declare(strict_types=1);
namespace Service\Api;

use Service\Controller as BaseController;

abstract class Controller extends BaseController {

	public function rules() {
		return array(
			'*' => '@'
		);
	}
}