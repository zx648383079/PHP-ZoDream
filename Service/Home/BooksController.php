<?php
namespace Service\Home;

use Domain\Model\OptionsModel;
use Domain\Model\PostsModel;
use Domain\Sogou\Main;
use Zodream\Infrastructure\Traits\AjaxTrait;

class BooksController extends Controller {
	use AjaxTrait;
	function indexAction() {
		//Main::search('土豆');
		$this->ajaxReturn(array(
			'title' => '这是测试',
			'content' => '跟鬼鬼鬼鬼鬼鬼鬼鬼鬼鬼鬼鬼鬼鬼鬼鬼鬼鬼急急急急急急急急急急急急急急急经济',
			'forward' => 'http://fff.ggg',
			'next' => 'http://ccc.ccc'
		));
	}
}