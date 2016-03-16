<?php
namespace Service\Home;

use Domain\Model\OptionsModel;
use Domain\Model\PostsModel;
class AboutController extends Controller {
	function indexAction() {
		$model = new OptionsModel();
		$posts = new PostsModel();
		$this->show('about', array(
				'title' => '关于我们',
				'data' => $model->findPage('about'),
				'news' => $posts->findNew()
		));
	}
}