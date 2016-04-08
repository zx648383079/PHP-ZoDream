<?php
namespace Domain\Form\Home;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\Home\PasswordResetModel;
class PasswordResetForm extends Form {
	public function get($id) {
		$model = new PasswordResetModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::isPost()) {
			return ;
		}
		$data = Request::post('token,email,create_at');
		if (!$this->validate($data, array(
			'token' => 'required',
			'email' => 'required',
			'create_at' => 'required'
		))) {
			$this->send($data);
			$this->send('error', '验证失败！');
			return;
		}
		$model = new PasswordResetModel();
		$result = $model->add($data);
		if (empty($result)) {
			$this->send($data);
			$this->send('error', '服务器出错了！');
			return;
		}
	}
}