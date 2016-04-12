<?php
namespace Service\Admin;

use Domain\Model\EmpireModel;

class ShopController extends Controller {
	/**
	 *	所有订单
	 */
	function indexAction() {
		$page = EmpireModel::query('enewsshopdd')->getPage('order by id desc', 'ddid,ddno,ddtime,userid,username,outproduct,haveprice,checked,truename,psid,psname,pstotal,alltotal,payfsid,payfsname,payby,alltotalfen,fp,fptotal,pretotal');
		$this->show(array(
			'page' => $page
		));
	}

	/**
	 * 优惠码
	 */
	function codeAction() {
		$page = EmpireModel::query('enewsshop_precode')->getPage('order by id desc', 'id,prename,precode,pretype,premoney,reuse,addtime,endtime');
		$this->show(array(
			'page' => $page
		));
	}

	function payAction() {
		$page = EmpireModel::query('enewsshoppayfs')->getPage('order by payid');
		$this->show(array(
			'page' => $page
		));
	}

	/**
	 * 配送
	 */
	function distributionAction() {
		$page = EmpireModel::query('enewsshopps')->getPage('order by pid');
		$this->show(array(
			'page' => $page
		));
	}

	function settingAction() {
		$this->show();
	}
}