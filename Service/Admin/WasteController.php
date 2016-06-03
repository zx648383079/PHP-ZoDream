<?php
namespace Service\Admin;
/**
 * 废料科普
 */
use Domain\Model\EmpireModel;

class WasteController extends Controller {
	function indexAction() {
		$page = EmpireModel::query('waste')->getPage(null, 'id,code,name,update_at');
		$this->show(array(
			'title' => '废料科普管理',
			'page' => $page
		));
	}

	function addAction() {
		$this->show(array(
			'title' => '新增标准'
		));
	}

	function companyAction() {
		$page = EmpireModel::query('company')->getPage(null, 'id,name,charge,update_at');
		$this->show(array(
			'title' => '公司管理',
			'page' => $page
		));
	}
}