<?php
namespace Service\Home;

use Domain\Model\EmpireModel;
use Domain\Model\WeChat\WeChatModel;
use Infrastructure\HtmlExpand;

class WechatController extends Controller {
	function indexAction($tag = null) {
		if (empty($tag)) {
			$this->show(array(
				'title' => '微信'
			));
		}
		$wechat = new WeChatModel();
		$data = $wechat->findOne('tag = '.$tag);
		if (empty($data)) {
			$this->show(array(
				'title' => '微信'
			));
		}
		
	}
}