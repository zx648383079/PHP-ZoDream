<?php
namespace Service\Admin;

use Domain\Form\AccountForm;
use Zodream\Domain\Response\Redirect;
class AccountController extends Controller {
	protected $rules = array(
			'*'      => '?',
			'logout' => '@'
	);
	
	function indexAction() {
		$form = new AccountForm();
		$form->login();
		$this->show();
	}
	
	function sendAction() {
		$form = new AccountForm();
		$form->sendEmail();
		$this->show();
	}
	
	function resetAction() {
		$form = new AccountForm();
		$form->reset();
		$this->show();
	}
	
	function registerAction() {
		$form = new AccountForm();
		$form->register();
		$this->show();
	}
	
	function logoutAction() {
		$form = new AccountForm();
		$form->clearAccount();
		Redirect::to('account');
	}
}