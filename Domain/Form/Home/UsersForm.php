<?php
namespace Domain\Form\Home;

use Domain\Model\Home\PasswordResetModel;
use Zodream\Domain\Form;
use Zodream\Domain\Response\Redirect;
use Zodream\Domain\Routing\UrlGenerator;
use Zodream\Infrastructure\Cookie;
use Zodream\Infrastructure\Log;
use Zodream\Infrastructure\ObjectExpand\Hash;
use Zodream\Infrastructure\ObjectExpand\StringExpand;
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
use Zodream\Infrastructure\Request;
use Domain\Model\Home\UsersModel;
use Zodream\Infrastructure\Session;
use Zodream\Infrastructure\Mailer;
use Zodream\Infrastructure\Template;

class UsersForm extends Form {
	public function login() {
		if (!Request::getInstance()->isPost()) {

			return ;
		}
		$data = Request::getInstance()->post('email,password');
		Log::save($data, 'login');
		if (!$this->validate($data, array(
			'email'    => 'required|email',
			'password' => 'required|string:3-30'
		))) {
			$this->send($data);
			$this->send('error', '邮箱和密码不合法！');
			return;
		}
		$model = new UsersModel();
		$result = $model->findByEmail($data['email']);
		if (empty($result)) {
			$this->send('error', '邮箱未注册！');
			return;
		}
		if (!Hash::verify($data['password'], $result['password'])) {
			$this->send('error', '密码错误！');
			return;
		}
		if (Request::getInstance()->post('remember') != null) {
			$token = StringExpand::random(10);
			$model->updateById($result['id'], array('remember_token' => $token));
			Cookie::set('token', $token, 3600 * 24 * 30);
		}
		Session::getInstance()->set('user', $result);
		Redirect::to('/');
	}

	public function loginByToken() {
		$token = Cookie::get('token');
		if (empty($token)) {
			return;
		}
		$model = new UsersModel();
		$result = $model->findByToken($token);
		if (empty($result)) {
			return;
		}
		Session::getInstance()->set('user', $result);
		Redirect::to('/');
	}

	public function clearAccount() {
		Session::getInstance()->clear();
		Cookie::delete('token');
	}

	public function register() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('name,email,password,cpassword,agree');
		if (!$this->validate($data, array(
			'name'     => 'required|string:2-20',
			'email'    => 'required|email',
			'password' => 'required|confirm:cpassword|string:3-30',
			'agree'    => 'required'
		))) {
			$this->send($data);
			$this->send('error', '信息未填写完整！');
			return;
		}
		unset($data['cpassword'], $data['agree']);
		$data['password'] = Hash::make(($data['password']));
		$data['create_at'] = time();
		Log::save($data, 'register');
		$model = new UsersModel();
		$row = $model->add($data);
		if (!empty($row)) {
			$data['id'] = $row;
			Session::getInstance()->set('user', $data);
			Redirect::to('/');
		}
		$this->send('error', '服务器错误！');
	}

	public function sendEmail() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$email = Request::getInstance()->post('email');
		$user = new UsersModel();
		$data = $user->findByEmail($email);
		if (empty($data)) {
			$this->send('error', '邮箱有误或未注册！');
			return;
		}
		$model = new PasswordResetModel();
		$token = StringExpand::random(10);
		$result = $model->add(array(
			'email' => $email,
			'token' => $token
		));
		if (empty($result)) {
			$this->send(array(
				'email' => $email,
				'error' => '服务器错误！请重试！'
			));
			return;
		}
		$mailer = new Mailer();
		$mailer->addAddress($email, $data['name']);
		$template = new Template();
		$template->set(array(
			'name' => $data['name'],
			'time' => TimeExpand::format(),
			'url' => UrlGenerator::to('reset?token='.$token)
		));
		$success = $mailer->send('ZoDream-密码重置', $template->getText('mail.html'));
		if (false === $success) {
			$this->send(array(
				'email' => $email,
				'error' => '邮件发送失败！请重试！'
			));
			return;
		}
		$this->send('error', '邮件已发送至您的邮箱，请点击邮箱中的链接进行密码重置！');
	}

	public function reset() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('oldpassword,newpassword,cpassword');
		if (!$this->validate($data, array(
			'oldpassword'     => 'required|string:3-30',
			'newpassword' => 'required|confirm:cpassword|string:3-30'
		))) {
			$this->send('error', '信息未填写完整！');
			return;
		}
		$user = Session::getInstance()->get('user');
		if (!Hash::verify($data['oldpassword'], $user['password'])) {
			$this->send('error', '密码错误！');
			return;
		}
		$model = new UsersModel();
		$result = $model->updateById($user, array(
			'password' => Hash::make(($data['newpassword']))
		));
		if (empty($result)) {
			$this->send('error', '密码修改失败！');
			return;
		}
		$this->clearAccount();
		Redirect::to('account', 5, '密码修改成功！请重新登陆！');
	}

	public function resetByEmail() {
		$token = Request::getInstance()->get('token');
		if (empty($token)) {
			Redirect::to('account');
		}
		$model = new PasswordResetModel();
		$result = $model->findByToken($token);
		if (empty($result)) {
			Redirect::to('account');
		}
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('password,cpassword');
		if (!$this->validate($data, array(
			'password'     => 'required|confirm:cpassword|string:3-30'
		))) {
			$this->send('error', '密码不一致！');
			return;
		}
		$user = new UsersModel();
		$user->update(array(
			'password' => Hash::make($data['password'])
		), "email = '{$result['email']}'");
		Log::save($result, 'resetByEmail');
		$model->deleteById($result['id']);
		Redirect::to('account', 5, '密码重置成功！');
	}
}