<?php
namespace Service\Admin;
/**
 * 文件管理
 */
use Infrastructure\Environment;

class ResourceController extends Controller {
	function indexAction($file = null) {
		$data = Environment::getFileByDir($file);
		$this->show(array(
			'title' => $file. '-文件管理',
			'data' => $data,
			'file' => dirname($file)
		));
	}
}