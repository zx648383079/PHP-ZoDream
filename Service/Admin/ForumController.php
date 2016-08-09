<?php
namespace Service\Admin;
use Domain\Model\Forum\ForumModel;
use Domain\Model\Forum\ThreadModel;
use Domain\Model\Forum\ThreadPostModel;

/**
 * 论坛管理
 */


class ForumController extends Controller {
	function indexAction() {
		$data = ForumModel::findAll(array(
			'order' => 'parent, type'
		));
		return $this->show(array(
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
		$data = ThreadModel::find()->order('create_at')->page();
		return $this->show(array(
			'page' => $data
		));
	}

	function postAction() {
		$data = ThreadPostModel::find()->order('create_at')->page();
		return $this->show(array(
			'page' => $data
		));
	}
}