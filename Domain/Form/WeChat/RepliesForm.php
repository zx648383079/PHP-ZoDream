<?php
namespace Domain\Form\WeChat;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\WeChat\RepliesModel;
class RepliesForm extends Form {
	public function get($id) {
		$model = new RepliesModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('account_id,type,name,trigger_keywords,trigger_type,content,group_ids,created_at,updated_at,deleted_at');
		if (!$this->validate($data, array(
			'account_id' => 'required',
			'type' => 'required',
			'name' => 'required',
			'trigger_keywords' => 'required',
			'trigger_type' => 'required',
			'content' => 'required',
			'group_ids' => 'required',
			'created_at' => 'required',
			'updated_at' => 'required',
			'deleted_at' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$model = new RepliesModel();
		$model->add($data);
	}
}