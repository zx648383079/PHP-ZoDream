<?php
namespace Service\Admin;

use Domain\Form\Home\ProductClassesForm;
use Domain\Model\Home\ProductClassesModel;

class ClassesController extends Controller {
	protected $rules = array(
			'*' => '@'
	);
	
	function indexAction() {
		$form = new ProductClassesForm();
		$form->runAction('add');
		$model = new ProductClassesModel();
		$this->show('Products.classes', array(
			'title' => '所有发布',
			'page' => $model->findPage()
		));
	}
}