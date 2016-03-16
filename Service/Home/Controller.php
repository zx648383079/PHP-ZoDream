<?php
namespace Service\Home;

use Zodream\Domain\Routing\Controller as BaseController;
use Domain\Model\OptionsModel;
abstract class Controller extends BaseController {
	public function prepare() {
		$model = new OptionsModel();
		$this->send($model->findValue());
	}
}