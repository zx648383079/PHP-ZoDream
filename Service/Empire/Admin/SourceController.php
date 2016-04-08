<?php
namespace Service\Empire\Admin;

use Domain\Model\EmpireModel;
use Service\Empire\Controller;
class SourceController extends Controller {
	/**
	 * 信息来源
	 */
	function indexAction() {
		$page = EmpireModel::query('enewsbefrom')->getPage('order by befromid desc', 'sitename,siteurl,befromid');
		$this->show(array(
			'page' => $page
		));
	}

	function authorAction() {
		$page = EmpireModel::query('enewswriter')->getPage('order by wid desc', 'wid,writer,email');
		$this->show(array(
			'page' => $page
		));
	}

	function keywordAction() {
		$page = EmpireModel::query('enewskey')->getPage('order by keyid desc', 'keyid,keyname,keyurl,cid');
		$this->show(array(
			'page' => $page
		));
	}

	function filterAction() {
		$page = EmpireModel::query('enewswords')->getPage('order by wordid desc', 'wordid,oldword,newword');
		$this->show(array(
			'page' => $page
		));
	}
}