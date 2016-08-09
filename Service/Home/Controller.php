<?php
namespace Service\Home;

use Domain\Model\OptionModel;
use Zodream\Domain\Controller\Controller as BaseController;
use Zodream\Domain\Routing\Router;
use Zodream\Infrastructure\Request;
use Zodream\Infrastructure\Traits\AjaxTrait;

abstract class Controller extends BaseController {
	public function prepare() {
		$this->send(OptionModel::findOption(['autoload' => 'yes']));
	}

	
}