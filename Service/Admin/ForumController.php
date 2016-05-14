<?php
namespace Service\Admin;

/**
 * 论坛管理
 */
use Domain\Model\EmpireModel;

class ForumController extends Controller {
	function indexAction() {
		$data = EmpireModel::query('forum')->findAll(array(
			'order' => 'parent, type'
		));
		$this->show(array(
			'data' => $data
		));
	}

	function indexPost() {
		EmpireModel::query('forum')->save(array(
			'id' => '',
			'name' => 'required|string:1-100',
			'type' => 'required|enum:group,forum,sub',
			'parent' => 'int',
			'position' => 'int'
		));
	}

	function addModeratorAction() {
		$data = EmpireModel::query('moderator')->findAll(array(
			'order' => 'position'
		));
		$this->show(array(
			'data' => $data
		));
	}

	function threadAction() {
		$data = EmpireModel::query('thread')->getPage(array(
			'order' => 'create_at'
		));
		$this->show(array(
			'page' => $data
		));
	}

	function postAction() {
		$data = EmpireModel::query('thread_post')->getPage(array(
			'order' => 'create_at'
		));
		$this->show(array(
			'page' => $data
		));
	}
}