<?php
namespace Service\Account;

/**
 * 动态
 */
use Domain\Model\EmpireModel;
use Zodream\Domain\Authentication\Auth;

class DynamicController extends Controller {
	function indexAction() {
		$page = EmpireModel::query('thread_post p')->getPage([
			'left' => [
				'thread t',
				't.id = p.thread_id'
			],
			'where' => [
				't.user_id' => Auth::user()['id'],
				'p.user_id != '.Auth::user()['id']
			],
			'order' => 'p.create_at desc'
		], [
			'content' => 'p.content',
			'title' => 't.title',
			'user_id' => 'p.user_id',
			'user_name' => 'p.user_name',
			'create_at' => 'p.create_at'
		]);
		$this->show([
			'title' => '最新动态',
			'page' => $page
		]);
	}

	function blogAction() {
		$page = EmpireModel::query('post')->getPage([
			'where' => ['user_id' => Auth::user()['id']],
			'order' => 'create_at desc'
		]);
		$this->show([
			'title' => '博客动态',
			'page' => $page
		]);
	}

	function forumAction() {
		$page = EmpireModel::query('thread')->getPage([
			'where' => ['user_id' => Auth::user()['id']],
			'order' => 'create_at desc'
		]);
		$this->show([
			'title' => '论坛动态',
			'page' => $page
		]);
	}
}