<?php
namespace Service\Admin;


use Domain\Form\Home\BlogsForm;
use Domain\Model\Home\BlogCategoryModel;
use Domain\Model\Home\BlogsModel;
class BlogsController extends Controller {
	protected $rules = array(
			'*' => '@'
	);
	
	function indexAction() {
		$model = new BlogsModel();
		$this->show(array(
			'title' => '所有博客',
			'page' => $model->findPage()
		));
	}
	
	function addAction() {
		$form = new BlogsForm();
		$form->set();
		$model = new BlogCategoryModel();
		$this->show('Blogs/edit', array(
			'title' => '新增发布',
			'category' => $model->find()
		));
	}
	
	function editAction($id) {
		$form = new BlogsForm();
		$form->set($id);
		$form->get($id);
		$model = new BlogCategoryModel();
		$this->show(array(
			'title' => '修改',
			'category' => $model->find()
		));
	}
	
	function deleteAction($id) {
		$model = new BlogsModel();
		$data  = $model->deleteById($id);
		$this->ajaxJson(array(
				'status' => $data
		));
	}
	
	function viewAction($id = 0) {
		$model = new BlogsModel();
		$this->show(array(
			'title' => '查看',
			'post' => $model->findView($id)
		));
	}
}