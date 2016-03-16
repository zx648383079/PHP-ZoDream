<?php
namespace Service\Home;

class SearchController extends Controller {
	function indexAction() {
		$this->show('search', array(
				'title' => '搜 ',
		));
	}
}