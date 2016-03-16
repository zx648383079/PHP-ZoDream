<?php
namespace Domain\Form;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Log;
use Zodream\Infrastructure\Request;
use Domain\Model\UsersModel;
use Zodream\Domain\Response\Redirect;
use Zodream\Infrastructure\Session;
class AccountForm extends Form {
	
	public function login() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('email,password');
		if (!$this->validate($data, array(
				'email'    => 'required|email',
				'password' => 'required|string:3-30'
		))) {
			$this->send($data);
			return;
		}
		$data['password'] = md5($data['password']);
		$model = new UsersModel();
		$result = $model->findOne($data, 'id');
		Log::save($data, 'login');
		if (!empty($result)) {
			$this->setAccount($result['id']);
			Redirect::to('/');
		}
	}
	
	public function setAccount($user) {
		if (Request::getInstance()->post('remember') != null) {
			Session::getInstance()->setLifeTime(30 * 24 * 3600);
		}
		Session::getInstance()->set('user', $user);
	}
	
	public function clearAccount() {
		Session::getInstance()->clear();
	}
	
	public function register() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('username,email,password,cpassword,agree');
		if (!$this->validate($data, array(
				'username' => 'required|string:2-20',
				'email'    => 'required|email',
				'password' => 'required|confirm:cpassword|string:3-30',
				'agree'    => 'required'
		))) {
			$this->send($data);
			return;
		}
		unset($data['cpassword'], $data['agree']);
		$data['password'] = md5($data['password']);
		$data['cdate'] = time();
		Log::save($data, 'register');
		$model = new UsersModel();
		$row = $model->add($data);
		if (empty($row)) {
			$this->login();
		}
	}
	
	public function sendEmail() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$email = Request::getInstance()->post('email');
		$this->send('email', $email);
	}
	
	public function reset() {
		
	}
}