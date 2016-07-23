<?php
namespace Service\Account;

use Domain\Model\Blog\CommentModel;
use Zodream\Domain\Access\Auth;
use Zodream\Infrastructure\Request\Post;

class HomeController extends Controller {
	function indexAction() {
		$page = CommentModel::find()->alias('c')
			->load([
			'left' => [
				'post p',
				'p.id = c.post_id'
			],
			'where' => [
				'p.user_id' => Auth::user()['id'],
				'c.user_id != '.Auth::user()['id']
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

	function infoAction() {
		return $this->show();
	}

	/**
	 * @param Post $post
	 */
	function infoPost($post) {
		$post->set('id', Auth::user()['id']);
		$row = EmpireModel::query('user')->save([
			'name' => 'required|string:4-40',
			'email' => 'required|email',
			'sex' => 'required|enum:男,女',
			'avatar' => 'required'
		], $post->get());
		if (empty($row)) {
			return;
		}
		Auth::user()->set($post->get());
		Auth::user()->save();
	}

	function avatarAction() {

	}

	function securityAction() {
		$this->show();
	}

	/**
	 * @param Post $post
	 */
	function securityPost($post) {
		$type = $post->get('type');
		switch (intval($type)) {
			case 5:
				
				break;
		}
	}

	function settingAction() {
		$this->show();
	}

}