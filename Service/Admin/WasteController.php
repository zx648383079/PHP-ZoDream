<?php
namespace Service\Admin;
/**
 * 废料科普
 */
class WasteController extends Controller {
	function indexAction() {
		$this->show(array(
			'title' => '废料科普管理'
		));
	}
}