<?php
namespace Service\Home;

use Domain\Model\Home\BlogsModel;

class BlogController extends Controller {
	function indexAction() {
		$model = new BlogsModel();
		$this->show('blog', array(
				'title' => '博客',
				'page' => $model->findPage()
		));
	}
    
    function viewAction($id = 0) {
		$this->show('single');
	}
}