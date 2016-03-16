<?php
namespace Service\Admin;

class HomeController extends Controller {
	protected $rules = array(
			'*'      => '@'
	);
	
	function indexAction() {
		$this->show();
	}
}