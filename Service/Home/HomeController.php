<?php
namespace Service\Home;

class HomeController extends Controller {
	function indexAction() {
		$this->show('index', array(
				'title' => '首页'
		));
	}
}