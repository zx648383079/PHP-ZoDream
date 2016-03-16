<?php
namespace Service\Home;

use Domain\Model\PostsModel;
class ServicesController extends Controller {
	function indexAction() {
		$model = new PostsModel();
		$this->show('services', array(
				'title' => '我们的服务',
				'page' => $model->findService(),
				'news' => $model->findNew()
		));
	}
}