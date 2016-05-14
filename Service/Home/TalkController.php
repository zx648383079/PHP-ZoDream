<?php
namespace Service\Home;

use Domain\Model\EmpireModel;
use Infrastructure\HtmlExpand;

class TalkController extends Controller {
	function indexAction() {
		$data = EmpireModel::query('talk')->findAll(array(
			'order' => 'create_at desc'
		));
		$this->show(array(
			'title' => '随想',
			'data' => HtmlExpand::getTree($data, function($arg, $composer) {
				return date('Y', $arg['create_at']) === date('Y', $composer['create_at']);
			})
		));
	}
}