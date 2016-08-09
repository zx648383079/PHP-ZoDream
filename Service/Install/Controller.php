<?php
namespace Service\Install;

use Zodream\Domain\Controller\Controller as BaseController;
use Domain\Model\Home\OptionsModel;
use Zodream\Infrastructure\Traits\AjaxTrait;

abstract class Controller extends BaseController {
	use AjaxTrait;
	public function prepare() {
		$model = new OptionsModel();
		$this->send($model->findValue());
	}
}