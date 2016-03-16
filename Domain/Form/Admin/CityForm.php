<?php
namespace Domain\Form\Admin;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\Admin\CityModel;
class CityForm extends Form {
	public function get($id) {
		$model = new CityModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('parent_id,name,level');
		if (!$this->validate($data, array(
			'parent_id' => 'required',
			'name' => 'required',
			'level' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$model = new CityModel();
		$model->add($data);
	}
}