<?php
namespace Service\Admin;

use Domain\Model\Home\CommentsModel;
use Domain\Model\Home\TasksModel;
use Zodream\Domain\Routing\Controller as BaseController;
use Domain\Model\Home\MessagesModel;
use Zodream\Domain\Routing\UrlGenerator;

abstract class Controller extends BaseController {
	public function prepare() {
		if (UrlGenerator::hasUri('account')) {
			return;
		}
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