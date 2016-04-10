<?php
namespace Service\Empire\Admin;

use Domain\Model\EmpireModel;
use Service\Empire\Controller;
class TalkController extends Controller {
	/**
	 *	所有订单
	 */
	function indexAction() {
		$page = EmpireModel::query('talk')->getPage('order by create_at desc');
		$this->show(array(
			'page' => $page
		));
	}

	function indexPost() {
		EmpireModel::query('talk')->save(array(
			'id' => '',
			'content' => 'required|string:1-255',
			'create_at' => '',
			'user_id' => ''
		));
	}
}