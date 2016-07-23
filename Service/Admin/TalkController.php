<?php
namespace Service\Admin;
use Domain\Model\Home\TalkModel;

/**
 * 随想
 */


class TalkController extends Controller {
	function indexAction() {
		$page = TalkModel::find()->order('create_at desc')->page();
		return $this->show(array(
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