<?php
namespace Service\Home;


use Domain\Form\Home\TasksForm;
use Domain\Model\Home\TasksModel;

class TaskController extends Controller {
	function indexAction() {
		$model = new TasksModel();
		$form = new TasksForm();
		$form->runAction('add');
		$this->show('task', array(
				'title' => '任务计划',
				'page' => $model->findPageWithFront()
		));
	}
}