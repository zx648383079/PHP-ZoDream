<?php
namespace Service\Home;

use Domain\Model\OptionModel;
use Zodream\Route\Controller\Controller as BaseController;

abstract class Controller extends BaseController {

    public $layout = 'main';

	public function prepare() {
		$this->send('layout_search_url', url('/blog'));
		//$this->send(OptionModel::findOption(['autoload' => 'yes']));
	}
}