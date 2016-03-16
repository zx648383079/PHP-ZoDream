<?php
namespace Service\Admin;

use Infrastructure\Environment;
use Zodream\Infrastructure\Request;
class SystemController extends Controller {
	protected $rules = array(
			'*' => '@'
	);
	
	function indexAction() {
		$dir = Request::getInstance()->get('dir', '/');
		$this->send(array(
				'title' => '文件系统',
				'forword' => str_replace('\\', '/', dirname($dir))
		));
		$this->show(Environment::getFileByDir($dir));
	}
	
	function openAction() {
		$this->send(array(
				'file' => Request::getInstance()->get('file')
		));
		$this->show();
	}
}