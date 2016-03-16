<?php
namespace Domain\Form;

use Zodream\Domain\Authentication\Auth;
use Zodream\Domain\Form;
use Zodream\Domain\Response\Redirect;
use Zodream\Infrastructure\Request;
use Domain\Model\TasksModel;
class TasksForm extends Form {
	public function get($id) {
		$model = new TasksModel();
		$this->send('task', $model->findById($id));
	}
	
	public function set($id = null) {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('name,content,status 0,progress 0');
		if (!$this->validate($data, array(
			'name' => 'required',
			'content' => 'required',
			'status' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$model = new TasksModel();
		if (is_null($id)) {
			$data['user_id'] = Auth::user();
			$data['udate'] = $data['cdate'] = time();
			$id = $model->add($data);
		} else {
			$data['udate'] = time();
			$model->updateById($id, $data);
		}
		if ('Admin' == APP_MODULE) {
			Redirect::to('tasks/view/id/' . $id);
		} else {
			$this->send('status', '提交成功，已在待审核列表中！');
		}
	}
}