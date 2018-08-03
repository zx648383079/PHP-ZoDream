<?php
namespace Service\Admin;
/**
 * 后台首页
 */
use Domain\Model\VisitLogModel;


class HomeController extends Controller {

	protected function rules() {
		return array(
			'*' => '@'
		);
	}

	function indexAction() {
		return $this->show(array(
			'name' => auth()->user()->name
		));
	}

	function mainAction() {
		$user = auth()->user();
		$search = VisitLogModel::getTopSearch();
		return $this->show(array(
			'name' => $user['name'],
			'num' => $user['login_num'],
			'ip' => $user['previous_ip'],
			'date' => $user['previous_at'],
			'search' => $search
		));
	}
}