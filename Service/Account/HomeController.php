<?php
namespace Service\Account;

use Domain\Model\EmpireModel;
use Zodream\Domain\Authentication\Auth;

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

	function securityAction() {
		$this->show();
	}

	function settingAction() {
		$this->show();
	}

}