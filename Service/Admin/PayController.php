<?php
namespace Service\Admin;

use Domain\Model\EmpireModel;

class PayController extends Controller {
	/**
	 *	支付参数配置
	 */
	function indexAction() {
		$this->show();
	}

	function apiAction() {
		$data = EmpireModel::query('enewspayapi')->select('order by myorder,payid', 'payid,paytype,payfee,paylogo,paysay,payname,isclose');
		$this->show(array(
			'data' => $data
		));
	}

	function recordAction() {
		$page = EmpireModel::query('enewspayrecord')->getPage('order by id desc', 'id,userid,username,orderid,money,posttime,paybz,type,payip');
		$this->show(array(
			'page' => $page
		));
	}
}