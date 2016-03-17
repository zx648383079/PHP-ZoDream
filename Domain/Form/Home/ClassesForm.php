<?php
namespace Domain\Form\Home;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\Home\ClassesModel;
class ClassesForm extends Form {
	public function get($id) {
		$model = new ClassesModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('id,name');
		if (!$this->validate($data, array(
			'id' => 'required',
			'name' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$model = new ClassesModel();
		$model->add($data);
	}
}