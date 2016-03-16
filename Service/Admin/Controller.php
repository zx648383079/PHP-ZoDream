<?php
namespace Service\Admin;

use Domain\Model\CommentsModel;
use Domain\Model\TasksModel;
use Zodream\Domain\Routing\Controller as BaseController;
use Domain\Model\MessagesModel;
abstract class Controller extends BaseController {
	public function prepare() {
		$model = new MessagesModel();
		$tasks = new TasksModel();
		$comments = new CommentsModel();
		$this->send(array(
			'usermessages' => $model->findTitle(),
			'noread' => $model->findNoReaded(),
			'newtasks' => $tasks->findNewTasks(),
			'newcomments' => $comments->findNewComments()
		));
	}
}