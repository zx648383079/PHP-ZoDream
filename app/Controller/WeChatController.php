<?php
namespace App\Controller;

use App\Lib\WeChat\Wechat;
class WechatController extends Controller {
	function indexAction() {
		Wechat::getInstance()->run();
		exit;
	}
} 