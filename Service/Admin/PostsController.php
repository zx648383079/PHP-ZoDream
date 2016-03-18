<?php
namespace Service\Admin;

use Domain\Model\Home\ClassesModel;
use Domain\Model\Home\PostsModel;
use Domain\Form\Home\PostsForm;
class PostsController extends Controller {
	protected $rules = array(
			'*' => '@'
	);
	
	function indexAction() {
		$model = new PostsModel();
		$this->show(array(
			'title' => '所有发布',
			'page' => $model->findPage()
		));
	}
	
	function addAction() {
		$form = new PostsForm();
		$form->set();
		$model = new ClassesModel();
		$this->show('Posts/edit', array(
			'title' => '新增发布',
			'classes' => $model->findByKind()
		));
	}
	
	function editAction($id) {
		$form = new PostsForm();
		$form->set($id);
		$form->get($id);
		$model = new ClassesModel();
		$this->show(array(
			'title' => '修改',
			'classes' => $model->findByKind()
		));
	}
	
	function deleteAction($id) {
		$model = new PostsModel();
		$data  = $model->deleteById($id);
		$this->ajaxJson(array(
				'status' => $data
		));
	}
	
	function viewAction($id = 0) {
		$model = new PostsModel();
		$this->show(array(
			'title' => '查看',
			'post' => $model->findById($id)
		));
	}
}