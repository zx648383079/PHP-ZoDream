<?php
namespace Service\Account;


use Zodream\Domain\Model;
use Zodream\Domain\Response\Redirect;
use Zodream\Domain\Routing\Controller as BaseController;
use Zodream\Domain\Routing\Url;
use Zodream\Infrastructure\Log;

abstract class Controller extends BaseController {
	protected function rules() {
		return array(
			'*' => '@'
		);
	}
}