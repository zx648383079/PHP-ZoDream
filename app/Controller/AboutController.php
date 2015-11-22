<?php
namespace App\Controller;

use App;

class AboutController extends Controller {
	function indexAction() {
		$this->show('about', array(
			'title' => '关于'
		));
	}
} 