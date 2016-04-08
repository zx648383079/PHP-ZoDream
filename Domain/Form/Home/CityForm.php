<?php
namespace Domain\Form\Home;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\Home\CityModel;
class CityForm extends Form {
	public function get($id) {
		$model = new CityModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::isPost()) {
			return ;
		}
		$data = Request::post('parent_id,name,level');
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