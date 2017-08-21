<?php
namespace Service\Home;

use Domain\Model\OptionModel;
use Zodream\Service\Controller\Controller as BaseController;

abstract class Controller extends BaseController {
	public function prepare() {
		//$this->send(OptionModel::findOption(['autoload' => 'yes']));
	}
}