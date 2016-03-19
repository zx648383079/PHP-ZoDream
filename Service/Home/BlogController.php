<?php
namespace Service\Home;

use Domain\Model\Home\BlogCategoryModel;
use Domain\Model\Home\BlogsModel;

class BlogController extends Controller {
	function indexAction() {
		$model = new BlogsModel();
		$category = new BlogCategoryModel();
		$this->show('blog', array(
			'title' => '博客',
			'page' => $model->findPage(),
			'category' => $category->find()
		));
	}
    
    function viewAction($id = 0) {
		$model = new BlogsModel();
		$blog = $model->findView($id);
		$this->show('single', array(
			'title' => $blog['title'],
			'data' => $blog
		));
	}
}