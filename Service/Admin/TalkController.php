<?php
namespace Service\Admin;

/**
 * 随想
 */
use Domain\Model\EmpireModel;

class TalkController extends Controller {
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