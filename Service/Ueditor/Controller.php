<?php
namespace Service\Ueditor;

use Zodream\Domain\Controller\BaseController;
use Zodream\Infrastructure\Traits\AjaxTrait;
abstract class Controller extends BaseController {
	
	protected function rules() {
		return [
			'*' => '@'
		];
	}
}