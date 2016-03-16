<?php
namespace Service\Home;


use Domain\Form\TasksForm;
use Domain\Model\TasksModel;

class TaskController extends Controller {
	function indexAction() {
		$model = new TasksModel();
		$form = new TasksForm();
		$form->set();
		$this->show('task', array(
				'title' => '任务计划',
				'page' => $model->findPageWithFront()
		));
	}
}