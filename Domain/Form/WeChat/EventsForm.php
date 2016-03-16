<?php
namespace Domain\Form\WeChat;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\WeChat\EventsModel;
class EventsForm extends Form {
	public function get($id) {
		$model = new EventsModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('account_id,key,type,value,created_at,updated_at');
		if (!$this->validate($data, array(
			'account_id' => 'required',
			'key' => 'required',
			'type' => 'required',
			'value' => 'required',
			'created_at' => 'required',
			'updated_at' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$model = new EventsModel();
		$model->add($data);
	}
}