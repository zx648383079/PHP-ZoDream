<?php
namespace Domain\Form\WeChat;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\WeChat\Fan_groupsModel;
class Fan_groupsForm extends Form {
	public function get($id) {
		$model = new Fan_groupsModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('account_id,group_id,title,fan_count,is_default,created_at,updated_at,deleted_at');
		if (!$this->validate($data, array(
			'account_id' => 'required',
			'group_id' => 'required',
			'title' => 'required',
			'fan_count' => 'required',
			'is_default' => 'required',
			'created_at' => 'required',
			'updated_at' => 'required',
			'deleted_at' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$model = new Fan_groupsModel();
		$model->add($data);
	}
}