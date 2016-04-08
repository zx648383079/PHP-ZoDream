<?php
namespace Service\Home;

use Domain\Form\Home\BlogCommentsForm;
use Domain\Model\Home\BlogCategoryModel;
use Domain\Model\Home\BlogCommentsModel;
use Domain\Model\Home\BlogsModel;
use Zodream\Infrastructure\Request;

class BlogController extends Controller {
	function indexAction() {
		$model = new BlogsModel();
		$category = new BlogCategoryModel();
		$this->show('blog', array(
			'title' => '博客',
			'page' => $model->findPage(Request::get('category')),
			'category' => $category->find()
		));
	}
    
    function viewAction($id = 0) {
		if (Request::isAjax()) {
			$this->comments($id);
		}
		$model = new BlogsModel();
		$blog = $model->findView($id);
		$this->show('single', array(
			'title' => $blog['title'],
			'data' => $blog,
			'links' => $model->getNextAndBefore($id)
		));
	}

	function comments($id) {
		$form = new BlogCommentsForm();
		$form->runAction('add');
		$page = Request::get('comments');
		$model = new BlogCommentsModel();
		$this->ajaxReturn($model->findPage($id, $page));
	}
}