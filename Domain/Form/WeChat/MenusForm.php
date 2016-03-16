<?php
namespace Domain\Form\WeChat;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\WeChat\MenusModel;
class MenusForm extends Form {
	public function get($id) {
		$model = new MenusModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('account_id,parent_id,name,type,key,sort,created_at,updated_at,deleted_at');
		if (!$this->validate($data, array(
			'account_id' => 'required',
			'parent_id' => 'required',
			'name' => 'required',
			'type' => 'required',
			'key' => 'required',
			'sort' => 'required',
			'created_at' => 'required',
			'updated_at' => 'required',
			'deleted_at' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$model = new MenusModel();
		$model->add($data);
	}
}