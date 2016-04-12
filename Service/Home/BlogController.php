<?php
namespace Service\Home;

class BlogController extends Controller {
	function indexAction() {
		$this->show(array(
			'title' => '随想'
		));
	}
}