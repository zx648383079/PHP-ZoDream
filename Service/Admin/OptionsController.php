<?php
namespace Service\Admin;

use Domain\Model\OptionsModel;
use Domain\Form\OptionsForm;
class OptionsController extends Controller {
	protected $rules = array(
			'*' => '@'
	);
	
	function indexAction() {
		$model = new OptionsModel();
		$data  = $model->findPage();
		$this->show($data);
	}
	
	function addAction() {
		$form = new OptionsForm();
		$form->set();
		$this->show();
	}
	
	function editAction($id) {
		$form = new OptionsForm();
		$form->set();
		$this->show();
	}
	
	function deleteAction($id) {
		$model = new OptionsModel();
		$data  = $model->deleteById($id);
		$this->ajaxJson(array(
				'status' => $data
		));
	}
	
	function viewAction($id = 0) {
		$model = new OptionsModel();
		$data  = $model->findById($id);
		$this->show($data);
	}
}