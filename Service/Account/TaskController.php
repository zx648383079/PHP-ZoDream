<?php
namespace Service\Account;

use Domain\Model\Blog\CommentModel;
use Zodream\Domain\Access\Auth;
use Zodream\Infrastructure\Http\Request\Post;

class TaskController extends Controller {
	function indexAction() {
		return $this->show([
			'title' => '我的任务',
		]);
	}

    function allAction() {
        return $this->show([
            'title' => '任务大厅',
        ]);
    }

    function publishAction() {
        return $this->show([
            'title' => '发布任务',
        ]);
    }

    function takeAction() {
        return $this->show([
            'title' => '接任务',
        ]);
    }

    function changeAction() {
        return $this->show([
            'title' => '更改任务状态',
        ]);
    }
}