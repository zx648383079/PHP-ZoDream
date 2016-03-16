<?php
namespace Service\Admin;

use Domain\Model\CommentsModel;
use Domain\Form\Comments;
class CommentsController extends Controller {
	protected $rules = array(
			'*' => '@'
	);
	
	function indexAction() {
		$model = new CommentsModel();
		$this->send('page', $model->findPage());
		$this->show();
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