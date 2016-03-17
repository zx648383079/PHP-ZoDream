<?php
namespace Service\Home;

use Domain\Model\Home\OptionsModel;
use Domain\Model\Home\PostsModel;
class AboutController extends Controller {
	function indexAction() {
		$model = new OptionsModel();
		$posts = new PostsModel();
		$this->show('about', array(
				'title' => '关于我们',
				'data' => $model->findPage('about'),
		));
	}
}