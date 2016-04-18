<?php
namespace Service\Admin;

/**
 * 友情链接
 */
use Domain\Model\EmpireModel;

class LinkController extends Controller {
	/**
	 * 友情链接
	 */
	function indexAction() {
		$data = EmpireModel::query('friendlink')->find();
		$this->show(array(
			'data' => $data
		));
	}

	function indexPost() {
		EmpireModel::query('friendlink')->save(array(
			'id' => '',
			'name' => 'required|string:1-100',
			'url' => 'required|url',
			'description' => '',
			'position' => 'int',
			'logo' => ''
		));
	}
}