<?php
namespace Service\Empire\Admin;

use Service\Empire\Controller;
use Zodream\Domain\Authentication\Auth;

class AdminController extends Controller {
	protected $rules = array(
		'*' => '@'
	);
	function indexAction() {
		$this->show(array(
		));
	}

	function mapAction() {
		$this->show(array(

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