<?php
namespace Service\Admin;
use Domain\Model\Navigation\NavigationCategoryModel;
use Domain\Model\Navigation\NavigationModel;

/**
 * 首页导航
 */


class NavigationController extends Controller {
	function indexAction() {
		$page = NavigationModel::find()->order('category_id,position')->page();
		$data = NavigationCategoryModel::findAll(array(
			'order' => 'position'
		), 'id,name');
		return $this->show(array(
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
		$data = NavigationCategoryModel::findAll(array(
			'order' => 'position'
		));
		return $this->show(array(
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