<?php
namespace Service\Admin;

use Domain\Form\Home\UsersForm;
use Zodream\Domain\Response\Redirect;
class AccountController extends Controller {
	protected $rules = array(
			'*'      => '?',
			'logout' => '@'
	);
	
	function indexAction() {
		$form = new UsersForm();
		$form->login();
		$this->show();
	}
	
	function sendAction() {
		$form = new UsersForm();
		$form->sendEmail();
		$this->show();
	}
	
	function resetAction() {
		$form = new UsersForm();
		$form->resetByEmail();
		$this->show();
	}
	
	function registerAction() {
		$form = new UsersForm();
		$form->register();
		$this->show();
	}
	
	function logoutAction() {
		$form = new UsersForm();
		$form->clearAccount();
		Redirect::to('account');
	}
}