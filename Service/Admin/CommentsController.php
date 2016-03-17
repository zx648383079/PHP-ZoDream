<?php
namespace Service\Admin;

use Domain\Model\Home\CommentsModel;
class CommentsController extends Controller {
	protected $rules = array(
			'*' => '@'
	);
	
	function indexAction() {
		$model = new CommentsModel();
		$this->show(array(
			'title' => '所有评论',
			'page' => $model->findPage()
		));
	}
	
	function addAction() {
		$form = new Comments();
		$form->set();
		$this->show();
	}
	
	function editAction($id) {
		$form = new Comments();
		$form->set();
		$this->show();
	}
	
	function deleteAction($id) {
		$model = new CommentsModel();
		$data  = $model->deleteById($id);
		$this->ajaxJson(array(
				'status' => $data
		));
	}
	
	function viewAction($id = 0) {
		$model = new CommentsModel();
		$data  = $model->findById($id);
		$this->show($data);
	}
}