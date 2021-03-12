<?php
namespace Service\Account;


use Service\Controller as BaseController;

abstract class Controller extends BaseController {
	public function rules() {
		return array(
			'*' => '*'
		);
	}
}