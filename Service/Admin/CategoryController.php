<?php
namespace Service\Admin;

use Domain\Form\Home\BlogCategoryForm;
use Domain\Model\Home\BlogCategoryModel;

class CategoryController extends Controller {
	protected $rules = array(
			'*' => '@'
	);
	
	function indexAction() {
		$form = new BlogCategoryForm();
		$form->set();
		$model = new BlogCategoryModel();
		$this->show('blogs.category', array(
			'title' => '所有发布',
			'page' => $model->findPage()
		));
	}
}