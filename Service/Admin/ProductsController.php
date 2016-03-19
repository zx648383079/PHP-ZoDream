<?php
namespace Service\Admin;


use Domain\Form\Home\BlogsForm;
use Domain\Form\Home\ProductsForm;
use Domain\Model\Home\BlogCategoryModel;
use Domain\Model\Home\BlogsModel;
use Domain\Model\Home\ProductClassesModel;
use Domain\Model\Home\ProductsModel;

class ProductsController extends Controller {
	protected $rules = array(
			'*' => '@'
	);
	
	function indexAction() {
		$model = new ProductsModel();
		$this->show(array(
			'title' => '所有项目',
			'page' => $model->findPage()
		));
	}
	
	function addAction() {
		$form = new ProductsForm();
		$form->runAction('fill');
		$model = new ProductClassesModel();
		$this->show('Products/edit', array(
			'title' => '新增发布',
			'classes' => $model->find()
		));
	}
	
	function editAction($id) {
		$form = new BlogsForm();
		$form->runAction('fill');
		$form->get($id);
		$model = new ProductClassesModel();
		$this->show(array(
			'title' => '修改',
			'classes' => $model->find(),
			'id' => $id
		));
	}
	
	function deleteAction($id) {
		//$model = new BlogsModel();
		//$data  = $model->deleteById($id);
		$this->ajaxJson(array(
				'status' => $data
		));
	}
	
	function viewAction($id = 0) {
		$model = new ProductsModel();
		$this->show(array(
			'title' => '查看',
			'product' => $model->findView($id)
		));
	}
}