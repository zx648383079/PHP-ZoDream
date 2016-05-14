<?php
namespace Service\Admin;

/**
 * 首页导航
 */
use Domain\Model\EmpireModel;

class NavigationController extends Controller {
	function indexAction() {
		$page = EmpireModel::query('navigation')->getPage('order by category_id,position');
		$data = EmpireModel::query('navigation_category')->findAll(array(
			'order' => 'position'
		), 'id,name');
		$this->show(array(
			'page' => $page,
			'category' => $data
		));
	}

	function indexPost() {
		EmpireModel::query('navigation')->save(array(
			'id' => '',
			'name' => 'required|string:1-100',
			'url' => 'required|string:1-255',
			'category_id' => 'required|int',
			'position' => 'int',
			'user_id' => ''
		));
	}

	function categoryAction() {
		$data = EmpireModel::query('navigation_category')->findAll(array(
			'order' => 'position'
		));
		$this->show(array(
			'data' => $data
		));
	}

	function categoryPost() {
		EmpireModel::query('navigation_category')->save(array(
			'id' => '',
			'name' => 'required|string:1-20',
			'position' => 'int',
			'user_id' => ''
		));
	}
}