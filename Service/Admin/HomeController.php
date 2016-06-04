<?php
namespace Service\Admin;
/**
 * 后台首页
 */
use Zodream\Domain\Authentication\Auth;

class HomeController extends Controller {

	protected function rules() {
		return array(
			'*' => '@'
		);
	}

	function indexAction() {
		$this->show(array(
			'name' => Auth::user()['name']
		));
	}

	function mainAction() {
		$user = Auth::user();
		$this->show(array(
			'name' => $user['name'],
			'num' => $user['login_num'],
			'ip' => $user['previous_ip'],
			'date' => $user['previous_at']
		));
	}
}