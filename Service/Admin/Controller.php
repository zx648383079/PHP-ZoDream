<?php
namespace Service\Admin;


use Zodream\Service\Controller\Controller as BaseController;

abstract class Controller extends BaseController {
	
	protected function rules() {
		return array(
			'*' => '*'
		);
	}


	public function prepare() {
		/*$model = new MessagesModel();
		$tasks = new TasksModel();
		$this->send(array(
			'usermessages' => $model->findTitle(),
			'noread' => $model->findNoReaded(),
			'newtasks' => $tasks->findNewTasks()
		));*/
	}
}