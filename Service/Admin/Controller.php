<?php
namespace Service\Admin;


use Zodream\Domain\Model;
use Zodream\Domain\Response\Redirect;
use Zodream\Domain\Controller\Controller as BaseController;
use Zodream\Infrastructure\Log;
use Zodream\Infrastructure\Traits\AjaxTrait;

abstract class Controller extends BaseController {
	
	protected function rules() {
		return array(
			'*' => 'admin'
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