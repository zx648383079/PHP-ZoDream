<?php
namespace Domain\Form\Admin;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\Admin\OptionsModel;
class OptionsForm extends Form {
	public function get($id) {
		$model = new OptionsModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('name,value');
		if (!$this->validate($data, array(
			'name' => 'required',
			'value' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$model = new OptionsModel();
		$model->add($data);
	}
}