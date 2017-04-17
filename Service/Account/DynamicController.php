<?php
namespace Service\Account;

/**
 * 动态
 */

use Domain\Model\Blog\BlogModel;
use Domain\Model\Forum\ThreadModel;
use Domain\Model\Forum\ThreadPostModel;
use Zodream\Domain\Access\Auth;

class DynamicController extends Controller {
	function indexAction() {
		$page = ThreadPostModel::find()->alias('p')->load([
			'left' => [
				'thread t',
				't.id = p.thread_id'
			],
			'where' => [
				't.user_id' => Auth::user()['id'],
				'p.user_id != '.Auth::user()['id']
			],
			'order' => 'p.create_at desc'
		])->select([
			'content' => 'p.content',
			'title' => 't.title',
			'user_id' => 'p.user_id',
			'user_name' => 'p.user_name',
			'create_at' => 'p.create_at'
		])->page();
		return $this->show([
			'title' => '最新动态',
			'page' => $page
		]);
	}

	function blogAction() {
		$page = BlogModel::find()->load([
			'where' => ['user_id' => Auth::user()['id']],
			'order' => 'create_at desc'
		])->page();
		return $this->show([
			'title' => '博客动态',
			'page' => $page
		]);
	}

	function forumAction() {
		$page = ThreadModel::find()->load([
			'where' => ['user_id' => Auth::user()['id']],
			'order' => 'create_at desc'
		])->page();
		return $this->show([
			'title' => '论坛动态',
			'page' => $page
		]);
	}
}