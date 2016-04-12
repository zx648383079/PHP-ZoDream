<?php
namespace Service\Admin;

use Domain\Model\EmpireModel;

class NewsController extends Controller {
	/**
	 * 信息来源
	 */
	function indexAction() {
		$page = EmpireModel::query('')->getPage(
			' ',
			'id,classid,isurl,titleurl,isqf,havehtml,istop,isgood,firsttitle,ismember,userid,username,plnum,totaldown,onclick,newstime,truetime,lastdotime,titlepic,title');
		$this->show(array(
			'page' => $page
		));
	}

	/**
	 * 归档
	 */
	function fileAction() {
		$page = EmpireModel::query(' ')->getPage(
			' ',
			'id,classid,isurl,titleurl,isqf,havehtml,istop,isgood,firsttitle,ismember,userid,username,plnum,totaldown,onclick,newstime,truetime,lastdotime,titlepic,title');
		$this->show(array(
			'page' => $page
		));
	}
}