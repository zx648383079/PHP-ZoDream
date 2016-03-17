<?php
namespace Service\Admin;

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
		$this->show('Posts/edit');
	}
	
	function editAction($id) {
		$form = new PostsForm();
		$form->set($id);
		$form->get($id);
		$this->show();
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
		$this->send('post', $model->findById($id));
		$this->show();
	}
}