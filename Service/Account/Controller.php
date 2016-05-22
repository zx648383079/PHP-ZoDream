<?php
namespace Service\Account;

use Domain\Model\EmpireModel;
use Zodream\Domain\Model;
use Zodream\Domain\Response\Redirect;
use Zodream\Domain\Routing\Controller as BaseController;
use Zodream\Domain\Routing\Url;
use Zodream\Infrastructure\Log;

abstract class Controller extends BaseController {
	protected function rules() {
		return array(
			'*' => '?'
		);
	}
}