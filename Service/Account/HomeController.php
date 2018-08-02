<?php
namespace Service\Account;

use Domain\Model\Blog\CommentModel;
use Zodream\Domain\Access\Auth;
use Zodream\Infrastructure\Http\Request\Post;

class HomeController extends Controller {
	function indexAction() {
		$page = CommentModel::find()->alias('c')
			->load([
			'left' => [
				'post p',
				'p.id = c.post_id'
			],
			'where' => [
				'p.user_id' => auth()->user()['id'],
				'c.user_id != '.auth()->user()['id']
			],
			'order' => 'c.create_at desc'
		])->select([
				'content' => 'c.content',
				'title' => 'p.title',
				'user_id' => 'c.user_id',
				'user_name' => 'c.user_name',
				'create_at' => 'c.create_at'
			])->page();
		return $this->show([
			'title' => '最新动态',
			'page' => $page
		]);
	}


}