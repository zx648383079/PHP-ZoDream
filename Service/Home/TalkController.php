<?php
namespace Service\Home;

use Domain\Model\Home\TalkModel;
use Infrastructure\HtmlExpand;

class TalkController extends Controller {
	function indexAction() {
		$data = TalkModel::order('create_at desc')->all();
		return $this->show(array(
			'title' => '随想',
			'data' => HtmlExpand::getTree($data, function($arg, $composer) {
				return date('Y', $arg['create_at']) === date('Y', $composer['create_at']);
			})
		));
	}
}