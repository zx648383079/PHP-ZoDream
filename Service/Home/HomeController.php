<?php
namespace Service\Home;

use Domain\Model\Home\OptionsModel;
use Domain\Model\Home\PostsModel;
class HomeController extends Controller {
	function indexAction() {
		$model = new OptionsModel();
		$posts = new PostsModel();
		$this->show('index', array(
				'title' => '首页',
				'data' => $model->findPage('index'),
		));
	}
}