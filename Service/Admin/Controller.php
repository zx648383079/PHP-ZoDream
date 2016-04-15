<?php
namespace Service\Admin;

use Zodream\Domain\Routing\Controller as BaseController;
use Zodream\Domain\Routing\UrlGenerator;
abstract class Controller extends BaseController {
	protected function rules() {
		return array(
			'*' => '@'
		);
	}


	public function prepare() {
		if (UrlGenerator::hasUri('account')) {
			return;
		}
		/*$model = new MessagesModel();
		$tasks = new TasksModel();
		$this->send(array(
			'usermessages' => $model->findTitle(),
			'noread' => $model->findNoReaded(),
			'newtasks' => $tasks->findNewTasks()
		));*/
	}
}