<?php
namespace Domain\Form\Home;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\Home\MobileModel;
class MobileForm extends Form {
	public function get($id) {
		$model = new MobileModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('id,number,city,type,city_code,postcode');
		if (!$this->validate($data, array(
			'id' => 'required',
			'number' => 'required',
			'city' => 'required',
			'type' => 'required',
			'city_code' => 'required',
			'postcode' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$model = new MobileModel();
		$model->add($data);
	}
}