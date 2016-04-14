<?php
namespace Service\Home;

use Domain\Model\EmpireModel;

class ForumController extends Controller {
	function indexAction() {
		$data = EmpireModel::query('forum')->find(array(
			'where' => array(
				'type' => array(
					'in', 
					array(
						'group',
						'forum'
					)
				)
			),
			'order' => 'parent'
		));
		$this->show(array(
			'title' => '论坛',
			'data' => $data
		));
	}

	function threadAction() {
		$sub = EmpireModel::query('forum')->find(array(
			'where' => array(
				'type' => 'sub'
			)
		));
		$page = EmpireModel::query('thread')->getPage(array(
			'order' => 'create_at desc'
		));
		$this->show(array(
			'sub' => $sub,
			'page' => $page
		));
	}

	function postAction() {
		$page = EmpireModel::query('thread_post')->find(array(
			'order' => 'create_at'
		));
		$this->show(array(
			'page' => $page
		));
	}
}