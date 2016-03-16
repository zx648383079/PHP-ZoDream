<?php
namespace Service\Home;

use Domain\Model\OptionsModel;
use Domain\Model\PostsModel;
class HomeController extends Controller {
	function indexAction() {
		$model = new OptionsModel();
		$posts = new PostsModel();
		$this->show('index', array(
				'title' => '首页',
				'data' => $model->findPage('index'),
				'product' => $posts->findNewProduct(),
				'service' => $posts->findNewService(),
				'blog' => $posts->findBlog(),
				'download' => $posts->findDownload()
		));
	}
}