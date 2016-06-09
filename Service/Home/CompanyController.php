<?php
namespace Service\Home;

use Domain\Model\EmpireModel;
use Domain\Model\WeChat\WeChatModel;
use Domain\WeChat\Subscribe;
use Infrastructure\HtmlExpand;

class CompanyController extends Controller {
	function indexAction($search = null) {
		$where = null;
		if (!empty($search)) {
			$args = explode(' ', $search);
			foreach ($args as $item) {
				$where[] = "name like '%{$item}%'";
				$where[] = ["product like '%{$item}%'", 'or'];
				$where[] = ["demand like '%{$item}%'", 'or'];
			}
		}
		$page = EmpireModel::query('company')->getPage();
		$this->show(array(
			'title' => '公司供求',
			'page' => $page
		));
	}
	
	function viewAction($id) {
		$data = EmpireModel::query('company')->findOne($id);
		$models = EmpireModel::query('company_comment')->findAll([
			'where' => [
				'company_id' => $id
			],
			'order' => 'create_at desc'
		]);
		$this->show(array(
			'title' => '查看 '.$data['name'],
			'data' => $data,
			'models' => $models
		));
	}
}