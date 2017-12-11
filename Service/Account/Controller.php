<?php
namespace Service\Account;


use Zodream\Route\Controller\Controller as BaseController;

abstract class Controller extends BaseController {
	protected function rules() {
		return array(
			'*' => '*'
		);
	}
}