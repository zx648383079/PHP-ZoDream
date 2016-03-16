<?php
namespace Domain\Form\WeChat;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\WeChat\Password_resetsModel;
class Password_resetsForm extends Form {
	public function get($id) {
		$model = new Password_resetsModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('email,token,created_at');
		if (!$this->validate($data, array(
			'email' => 'required',
			'token' => 'required',
			'created_at' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$model = new Password_resetsModel();
		$model->add($data);
	}
}