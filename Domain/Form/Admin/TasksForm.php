<?php
namespace Domain\Form\Admin;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\Admin\TasksModel;
class TasksForm extends Form {
	public function get($id) {
		$model = new TasksModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('name,content,progress,status,user_id,udate,cdate');
		if (!$this->validate($data, array(
			'name' => 'required',
			'content' => 'required',
			'progress' => 'required',
			'status' => 'required',
			'user_id' => 'required',
			'udate' => 'required',
			'cdate' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$model = new TasksModel();
		$model->add($data);
	}
}