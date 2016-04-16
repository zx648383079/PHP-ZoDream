<?php
namespace Service\Admin;

/**
 * 左侧菜单
 */
use Domain\Model\EmpireModel;

class MenuController extends Controller {
	/*
	 * 信息管理
	 */
	function indexAction() {
		$this->show(array(

		));
	}

	function systemAction() {
		$this->show(array(

		));
	}

	function shopAction() {
		$this->show(array(

		));
	}

	/*
	 * 栏目管理
	 */
	function columnAction() {
		$this->show(array(

		));
	}

	function templateAction() {
		$this->show(array(

		));
	}

	function wechatAction() {
		$this->show(array(

		));
	}

	function extendAction() {
		$data = EmpireModel::query()->getMenu(3);
		$this->show(array(
			'data' => $data
		));
	}

	function toolAction() {
		$data = EmpireModel::query()->getMenu(2);
		$this->show(array(
			'data' => $data
		));
	}

	function userAction() {
		$this->show(array(

		));
	}

	function otherAction() {
		$this->show(array(

		));
	}
}