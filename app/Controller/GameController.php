<?php
namespace App\Controller;

use App;

class GameController extends Controller {
	function indexAction($id = 0)
	{
		if ($id > 0) {
			$this->show('game.view', array(
					'title' => '首页'
			));
		}
		$this->show('game.index', array(
			'title' => '首页'
		));
	}
} 