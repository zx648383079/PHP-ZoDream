<?php
namespace Domain\Form;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\UsersModel;
class UsersForm extends Form {
	public function get($id) {
		$model = new UsersModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('username,email,password,cdate');
		if (!$this->validate($data, array(
			'username' => 'required',
			'email' => 'required',
			'password' => 'required',
			'cdate' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$model = new UsersModel();
		$model->add($data);
	}
}