<?php
namespace Service\Empire\Admin;

use Domain\Model\EmpireModel;
use Service\Empire\Controller;
class ImageController extends Controller {
	/**
	 *	管理图片信息分类
	 */
	function indexAction() {
		$data = EmpireModel::query('enewspicclass')->select('order by classid desc', 'classid,classname');
		$this->show(array(
			'data' => $data
		));
	}

	function informationAction() {
		$page = EmpireModel::query('enewspic')->getPage('order by picid desc', 'picid,title,pic_url,url,pic_width,pic_height,open_pic,border,pictext');
		$this->show(array(
			'page' => $page
		));
	}
}