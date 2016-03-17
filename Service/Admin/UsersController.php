<?php
namespace Service\Admin;

use Domain\Form\Home\UsersForm;
use Domain\Model\Home\UsersModel;
use Zodream\Domain\Authentication\Auth;

class UsersController extends Controller {
	protected $rules = array(
			'*' => '@'
	);
	
	function indexAction() {
		$model = new UsersModel();
		$this->show(array(
			'title' => '用户管理',
			'users' => $model->findPage()
		));
	}
	
	function resetAction() {
		$form = new UsersForm();
		$form->reset();
		$this->show();
	}
	
	function rolesAction($id = 0) {
		$model = new UsersModel();
		$data  = $model->findById($id);
		$this->show($data);
	}
	
	function infoAction() {
		$this->show(array(
			'title' => '用户信息查看',
			'user' => Auth::user()
		));
	}
}