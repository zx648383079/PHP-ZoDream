<?php
namespace Service\Home;

class ForumController extends Controller {
	function indexAction() {
		$this->show(array(
			'title' => '随想'
		));
	}
}