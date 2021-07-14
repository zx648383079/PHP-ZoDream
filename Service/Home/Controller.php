<?php
namespace Service\Home;

use Zodream\Disk\File;
use Zodream\Route\Controller\Controller as BaseController;

abstract class Controller extends BaseController {

    public File|string $layout = 'main';

	public function prepare() {
		$this->send('layout_search_url', url('/blog'));
		//$this->send(OptionModel::findOption(['autoload' => 'yes']));
	}
}