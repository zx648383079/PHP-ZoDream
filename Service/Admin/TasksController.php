<?php
namespace Service\Admin;

use Domain\Form\Home\TasksForm;
use Domain\Model\Home\TasksModel;

class TasksController extends Controller {
	protected $rules = array(
			'*' => '@'
	);
	
	function indexAction() {
		$model = new TasksModel();
		$this->send('page', $model->findPage());
		$this->show();
	}
	
	function addAction() {
		$form = new TasksForm();
		$form->runAction('add');
		$this->show('Tasks/edit');
	}

	function editAction($id) {
		$form = new TasksForm();
		$form->runAction('add');
		$form->get($id);
		$this->show();
	}
	
	function viewAction($id = 0) {
		$model = new TasksModel();
		$this->send('task', $model->findById($id));
		$this->show();
	}
}