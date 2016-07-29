<?php
namespace Service\Home;


use Domain\Model\Company\CompanyModel;
use Domain\Model\Waste\WasteCompanyModel;
use Domain\Model\Waste\WasteModel;
use Infrastructure\HtmlExpand;

class WasteController extends Controller {
	function indexAction($search = null) {
		$where = null;
		if (!empty($search)) {
			$args = explode(' ', $search);
			foreach ($args as $item) {
				$where[] = "w.code like '%{$item}%'";
				$where[] = ["w.name like '%{$item}%'", 'or'];
			}
		}
		$data = WasteModel::find()->alias('w')->load(array(
			'right' => array(
				'waste_company wc',
				'wc.waste_id = w.id'
			),
			'left' => array(
				'company c',
				'c.id = wc.company_id'
			),
			'where' => $where,
			'order' => 'w.id asc'
		))->select(array(
			'id' => 'w.id',
			'code' => 'w.code',
			'name' => 'w.name',
			'content' => 'w.content',
			'company_id' => 'c.id',
			'company' => 'c.name',
			'phone' => 'c.phone',
			'update_at' => 'w.update_at'
		))->all();
		return $this->show(array(
			'title' => '废料科普',
			'data' => HtmlExpand::getTree($data, 'id')
		));
	}
	
	function companyAction($id) {
		$id = intval($id);
		$data = CompanyModel::findOne($id);
		$models = WasteCompanyModel::find()->alias('wc')->load([
			'left' => [
				'waste w',
				'wc.waste_id = w.id'
			],
			'where' => [
				'wc.company_id' => $id
			]
		])->select([
			'id' => 'w.id',
			'code' => 'w.code',
			'name' => 'w.name'
		])->all();
		return $this->show(array(
			'title' => '查看 '.$data['name'],
			'data' => $data,
			'models' => $models 
		));
	}
	
	function viewAction($id) {
		$id = intval($id);
		$data = WasteModel::findOne($id);
		$models = WasteCompanyModel::find()->alias('wc')->load([
			'left' => [
				'company c',
				'wc.company_id = c.id'
			],
			'where' => [
				'wc.waste_id' => $id
			]
		])->select([
			'id' => 'c.id',
			'name' => 'c.name',
			'phone' => 'c.phone'
		])->all();
		return $this->show(array(
			'title' => '查看 '.$data['code'],
			'data' => $data,
			'models' => $models
		));
	}
}