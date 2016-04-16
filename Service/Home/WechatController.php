<?php
namespace Service\Home;

use Domain\Model\EmpireModel;
use Infrastructure\HtmlExpand;

class WechatController extends Controller {
	function indexAction($tag = null) {
		if (empty($tag)) {
			$this->show(array(
				'title' => '微信'
			));
		}
	}
}