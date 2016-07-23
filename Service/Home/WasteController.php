<?php
namespace Service\Home;


use Domain\Model\WeChat\WeChatModel;
use Domain\WeChat\Subscribe;
use Infrastructure\HtmlExpand;

class WasteController extends Controller {
	function indexAction($search = null) {
		$this->runCache('waste.index'.$search);
		$where = null;
		if (!empty($search)) {
			$args = explode(' ', $search);
			foreach ($args as $item) {
				$where[] = "w.code like '%{$item}%'";
				$where[] = ["w.name like '%{$item}%'", 'or'];
			}
		}
		$data = EmpireModel::query('waste w')->findAll(array(
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
		), array(
			'id' => 'w.id',
			'code' => 'w.code',
			'name' => 'w.name',
			'content' => 'w.content',
			'company_id' => 'c.id',
			'company' => 'c.name',
			'phone' => 'c.phone',
			'update_at' => 'w.update_at'
		));
		$this->show(array(
			'title' => '废料科普',
			'data' => HtmlExpand::getTree($data, 'id')
		));
	}
	
	function companyAction($id) {
		$id = intval($id);
		$data = EmpireModel::query('company')->findOne($id);
		$models = EmpireModel::query('waste_company wc')->findAll([
			'left' => [
				'waste w',
				'wc.waste_id = w.id'
			],
			'where' => [
				'wc.company_id' => $id
			]
		], [
			'id' => 'w.id',
			'code' => 'w.code',
			'name' => 'w.name'
		]);
		$this->show(array(
			'title' => '查看 '.$data['name'],
			'data' => $data,
			'models' => $models 
		));
	}
	
	function viewAction($id) {
		$id = intval($id);
		$data = EmpireModel::query('waste')->findOne($id);
		$models = EmpireModel::query('waste_company wc')->findAll([
			'left' => [
				'company c',
				'wc.company_id = c.id'
			],
			'where' => [
				'wc.waste_id' => $id
			]
		], [
			'id' => 'c.id',
			'name' => 'c.name',
			'phone' => 'c.phone'
		]);
		$this->show(array(
			'title' => '查看 '.$data['code'],
			'data' => $data,
			'models' => $models
		));
	}
}