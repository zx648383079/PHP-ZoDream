<?php
namespace Service\Home;


use Domain\Model\Company\CompanyCommentModel;
use Domain\Model\Company\CompanyModel;

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
		$page = CompanyModel::find()->where($where)->page();
		return $this->show(array(
			'title' => '公司供求',
			'page' => $page
		));
	}
	
	function viewAction($id) {
		$data = CompanyModel::findOne($id);
		$models = CompanyCommentModel::findAll([
			'where' => [
				'company_id' => $id
			],
			'order' => 'create_at desc'
		]);
		return $this->show(array(
			'title' => '查看 '.$data['name'],
			'data' => $data,
			'models' => $models
		));
	}
}