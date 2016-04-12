<?php
namespace Service\Admin;

use Domain\Model\EmpireModel;

class MemberController extends Controller {
	/*
	 * 管理会员
	 */
	function indexAction() {
		$page = EmpireModel::query('enewsmember')->getPage();
		$this->show(array(
			'page' => $page
		));
	}

	function clearAction() {
		$page = EmpireModel::query('enewsmember')->getPage();
		$this->show(array(
			'page' => $page
		));
	}

	function fieldAction() {
		$data = EmpireModel::query('enewsmemberf')->select('order by myorder,fid');
		$this->show(array(
			'data' => $data
		));
	}

	function groupAction() {
		$data = EmpireModel::query('enewsmembergroup')->select('order by groupid');
		$this->show(array(
			'data' => $data
		));
	}

	function formAction() {
		$page = EmpireModel::query('enewsmemberform')->getPage('order by fid desc', 'fid,fname');
		$this->show(array(
			'page' => $page
		));
	}

	function styleAction() {
		$page = EmpireModel::query('enewsspacestyle')->getPage('order by styleid desc');
		$this->show(array(
			'page' => $page
		));
	}

	function messageAction() {
		$page = EmpireModel::query('enewsmembergbook')->getPage('order by gid desc', 'gid,isprivate,uid,uname,ip,addtime,gbtext,retext,userid,eipport');
		$this->show(array(
			'page' => $page
		));
	}

	function feedbackAction() {
		$page = EmpireModel::query('enewsmemberfeedback')->getPage('order by fid desc', 'fid,title,uid,uname,addtime,userid');
		$this->show(array(
			'page' => $page
		));
	}

	function connectAction() {
		$data = EmpireModel::query('enewsmember_connect_app')->select('order by myorder,id');
		$this->show(array(
			'data' => $data
		));
	}

	function buyAction() {
		$page = EmpireModel::query('enewsbuygroup')->getPage('order by myorder,id', 'id,gname,gmoney,gfen,gdate');
		$this->show(array(
			'page' => $page
		));
	}

	function cardAction() {
		$page = EmpireModel::query('enewscard')->getPage('order by cardid desc', 'cardid,card_no,password,cardfen,money,endtime,cardtime,carddate');
		$this->show(array(
			'page' => $page
		));
	}

	function giftAction() {
		$this->show();
	}

	function emailAction() {
		$this->show();
	}

	function sendAction() {
		$this->show();
	}

	function deleteAction() {
		$this->show();
	}
}