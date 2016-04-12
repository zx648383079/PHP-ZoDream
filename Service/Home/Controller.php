<?php
namespace Service\Home;

use Domain\Model\EmpireModel;
use Zodream\Domain\Routing\Controller as BaseController;
use Zodream\Infrastructure\Traits\AjaxTrait;

abstract class Controller extends BaseController {
	use AjaxTrait;
	public function prepare() {
		$data = EmpireModel::query('option')->find(array(
			'where' => array(
				'autoload' => 'yes'
			)
		));
		foreach ($data as $item) {
			$this->send($item['name'], $item['value']);
		}
	}
}