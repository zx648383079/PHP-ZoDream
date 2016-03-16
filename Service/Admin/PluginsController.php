<?php
namespace Service\Admin;

class PluginsController extends Controller {
	protected $rules = array(
			'*' => '@'
	);
	
	function indexAction() {
		$this->show();
	}
	
	function shopAction() {
		$this->show();
	}
}