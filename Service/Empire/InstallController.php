<?php
namespace Service\Empire;

use Zodream\Infrastructure\Request;

class InstallController extends Controller {
	function indexAction() {
		if (file_exists('install.off')) {
			 $this->show('@《网站管理系统》安装程序已锁定。如果要重新安装，请删除<b>../install.off</b>文件！');
		}
	}

	function databaseAction() {
		$this->show();
	}

	/**
	 * 采集测试
	 */
	function spiderAction() {
		$content = file_get_contents('');
		$this->show('@<title>TEST</title><br>测试结果：<b>'.(empty($content) ?  '不':'').'支持采集</b>');
	}
}