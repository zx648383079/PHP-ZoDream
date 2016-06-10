<?php
namespace Service\Account;

use Domain\Model\EmpireModel;
use Zodream\Domain\Authentication\Auth;
use Zodream\Infrastructure\Request\Post;

class HomeController extends Controller {
	function indexAction() {
		$page = EmpireModel::query('comment c')->getPage([
			'left' => [
				'post p',
				'p.id = c.post_id'
			],
			'where' => [
				'p.user_id' => Auth::user()['id'],
				'c.user_id != '.Auth::user()['id']
			],
			'order' => 'c.create_at desc'
		], [
			'content' => 'c.content',
			'title' => 'p.title',
			'user_id' => 'c.user_id',
			'user_name' => 'c.user_name',
			'create_at' => 'c.create_at'
		]);
		$this->show([
			'title' => '最新动态',
			'page' => $page
		]);
	}

	function infoAction() {
		$this->show();
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

	function settingAction() {
		$this->show();
	}

}