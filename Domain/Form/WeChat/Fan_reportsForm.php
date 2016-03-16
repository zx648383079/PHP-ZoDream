<?php
namespace Domain\Form\WeChat;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\WeChat\Fan_reportsModel;
class Fan_reportsForm extends Form {
	public function get($id) {
		$model = new Fan_reportsModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('account_id,openid,type,created_at,updated_at');
		if (!$this->validate($data, array(
			'account_id' => 'required',
			'openid' => 'required',
			'type' => 'required',
			'created_at' => 'required',
			'updated_at' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$model = new Fan_reportsModel();
		$model->add($data);
	}
}