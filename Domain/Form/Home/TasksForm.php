<?php
namespace Domain\Form\Home;

use Zodream\Domain\Authentication\Auth;
use Zodream\Domain\Form;
use Zodream\Domain\Response\Redirect;
use Zodream\Infrastructure\Request;
use Domain\Model\Home\TasksModel;
class TasksForm extends Form {
	public function get($id) {
		$model = new TasksModel();
		$this->send('task', $model->findById($id));
	}

	public function add() {
		$data = Request::post('id,name,content,status 审核,progress 0');
		if (!$this->validate($data, array(
			'name' => 'required',
			'content' => 'required',
			'status' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$model = new TasksModel();
		$id = $model->fill($data);
		if ('Admin' == APP_MODULE) {
			Redirect::to('tasks/view/id/' . $id);
		} else {
			$this->send('status', '提交成功，已在待审核列表中！');
		}
	}
}