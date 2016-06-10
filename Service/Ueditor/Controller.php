<?php
namespace Service\Ueditor;

use Zodream\Domain\Routing\BaseController;
use Zodream\Infrastructure\Traits\AjaxTrait;
abstract class Controller extends BaseController {
	
	protected function rules() {
		return [
			'*' => '@'
		];
	}

	use AjaxTrait;
}