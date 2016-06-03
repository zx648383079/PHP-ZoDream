<?php
namespace Service\Home;

use Domain\Model\EmpireModel;
use Domain\Model\WeChat\WeChatModel;
use Domain\WeChat\Subscribe;
use Infrastructure\HtmlExpand;

class WasteController extends Controller {
	function indexAction($search = null) {
		$where = null;
		if (!empty($search)) {
			$args = explode(' ', $search);
			foreach ($args as $item) {
				$where[] = "w.code like '%{$item}%'";
				$where[] = "w.name like '%{$item}%'";
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
			'company' => 'c.name',
			'phone' => 'c.phone',
			'update_at' => 'w.update_at'
		));
		$this->show(array(
			'title' => '废料科普',
			'data' => HtmlExpand::getTree($data, 'id')
		));
	}
}