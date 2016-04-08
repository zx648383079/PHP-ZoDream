<?php
namespace Service\Home;

use Zodream\Domain\Routing\Controller as BaseController;
use Domain\Model\Home\OptionsModel;
use Zodream\Infrastructure\Traits\AjaxTrait;

abstract class Controller extends BaseController {
	use AjaxTrait;
	public function prepare() {
		$model = new OptionsModel();
		$this->send($model->findValue());
	}
}