<?php
namespace Service\Admin;

use Domain\Form\UsersForm;
use Domain\Model\UsersModel;
class UsersController extends Controller {
	protected $rules = array(
			'*' => '@'
	);
	
	function indexAction($page = 0) {
		$model = new UsersModel();
		$this->send('users', $model->findPage($page));
		$this->show();
	}
	
	function resetAction() {
		$form = new UsersForm();
		$form->set();
		$this->show();
	}
	
	function rolesAction($id = 0) {
		$model = new UsersModel();
		$data  = $model->findById($id);
		$this->show($data);
	}
	
	function infoAction() {
		$this->show();
	}
}