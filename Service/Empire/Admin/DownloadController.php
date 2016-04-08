<?php
namespace Service\Empire\Admin;

use Domain\Model\EmpireModel;
use Service\Empire\Controller;
class DownloadController extends Controller {
	/**
	 * 管理地址前缀
	 */
	function indexAction() {
		$data = EmpireModel::query('enewsdownurlqz')->select('order by urlid desc', 'urlid,urlname,url,downtype');
		$this->show(array(
			'data' => $data
		));
	}

	function recordAction() {
		$this->show();
	}

	function errorAction() {
		$page = EmpireModel::query('enewsdownerror')->getPage('order by errorid desc');
		$this->show(array(
			'page' => $page
		));
	}

	function levelAction() {
		$this->show();
	}

	function playerAction() {
		$data = EmpireModel::query('enewsplayer')->select('order by id', 'id,player,filename,bz');
		$this->show(array(
			'data' => $data
		));
	}
}