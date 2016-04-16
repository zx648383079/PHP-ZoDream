<?php
namespace Service\Admin;

/**
 * 微信
 */

class WechatController extends Controller {
	function indexAction() {
		$this->show(array(
		));
	}

	function addAction() {
		$this->show();
	}
}