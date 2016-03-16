<?php
namespace Domain\Form\WeChat;

use Zodream\Domain\Form;
use Zodream\Domain\Response\Redirect;
use Zodream\Infrastructure\Cookie;
use Zodream\Infrastructure\ObjectExpand\Hash;
use Zodream\Infrastructure\ObjectExpand\StringExpand;
use Zodream\Infrastructure\Request;
use Domain\Model\WeChat\UsersModel;
use Zodream\Infrastructure\Session;
class UsersForm extends Form {
	public function login() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('name,password');
		if (!$this->validate($data, array(
			'name'    => 'required',
			'password' => 'required'
		))) {
			$this->send(array(
				'name' => $data['name'],
				'error' => '输入有误'
			));
			return;
		}
		$password = $data['password'];
		unset($data['password']);
		$model = new UsersModel();
		$result = $model->findOne($data);
		if (empty($result) || !Hash::verify($password, $result['password'])) {
			$this->send('error', '用户未注册或密码错误');
			return;
		}
		if (Request::getInstance()->post('remember') != null) {
			$token = StringExpand::random(10);
			$model->updateById($result['id'], array('remember_token' => $token));
			$result['remember_token'] = $token;
			Cookie::set('token', $token, 3600 * 24 * 30);
		}
		Session::getInstance()->set('user', $result);
		Redirect::to('admin');
	}

	public function clearAccount() {
		Session::getInstance()->clear();
	}
}