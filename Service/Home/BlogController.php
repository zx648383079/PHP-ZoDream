<?php
namespace Service\Home;

use Domain\Model\PostsModel;
class BlogController extends Controller {
	function indexAction() {
		$model = new PostsModel();
		$this->show('blog', array(
				'title' => '我们的博客',
				'page' => $model->findPage(3)
		));
	}
    
    
}