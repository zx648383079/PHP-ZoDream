<?php
namespace Service\Home;

use Domain\Model\EmpireModel;
use Zodream\Domain\Routing\Controller as BaseController;
use Zodream\Domain\Routing\Router;
use Zodream\Infrastructure\Request;
use Zodream\Infrastructure\Traits\AjaxTrait;

abstract class Controller extends BaseController {
	public function prepare() {
		$data = EmpireModel::query('option')->findAll(array(
			'where' => array(
				'autoload' => 'yes'
			)
		));
		foreach ($data as $item) {
			$this->send($item['name'], $item['value']);
		}
	}

	
}