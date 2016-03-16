<?php
namespace Domain\Form\WeChat;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\WeChat\Message_resourcesModel;
class Message_resourcesForm extends Form {
	public function get($id) {
		$model = new Message_resourcesModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('account_id,detail,type,status,created_at,updated_at');
		if (!$this->validate($data, array(
			'account_id' => 'required',
			'detail' => 'required',
			'type' => 'required',
			'status' => 'required',
			'created_at' => 'required',
			'updated_at' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$model = new Message_resourcesModel();
		$model->add($data);
	}
}