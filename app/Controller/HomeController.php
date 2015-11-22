<?php
namespace App\Controller;

use App;

class HomeController extends Controller {
	function indexAction() {
		$search = App::$request->get('search');
		if ($search !== null) {
			$this->_search($search);
		}
		$this->show('index', array(
			'title' => '首页'
		));
	}
	
	private function _search($arg) {
		$data = array(
			array(
				''
			)	
		);
		$this->show('search', array(
			'title' => '搜'.$arg,
			'data'  => $data
		));
	}
} 