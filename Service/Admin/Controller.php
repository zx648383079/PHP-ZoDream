<?php
declare(strict_types=1);
namespace Service\Admin;

use Service\Controller as BaseController;
use Zodream\Disk\File;

abstract class Controller extends BaseController {

    protected File|string $layout = 'main';
	
	public function rules() {
		return [
		    '*' => '@'
        ];
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