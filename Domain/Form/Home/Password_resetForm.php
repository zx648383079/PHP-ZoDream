<?php
namespace Domain\Form\Home;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\Home\Password_resetModel;
class Password_resetForm extends Form {
	public function get($id) {
		$model = new Password_resetModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('token,email,create_at');
		if (!$this->validate($data, array(
			'token' => 'required',
			'email' => 'required',
			'create_at' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$model = new Password_resetModel();
		$model->add($data);
	}
}