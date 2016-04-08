<?php
namespace Service\Empire\Admin;

use Domain\Form\EmpireForm;
use Domain\Model\EmpireModel;
use Service\Empire\Controller;
use Zodream\Domain\Authentication\Auth;
use Zodream\Domain\Response\Redirect;
use Zodream\Infrastructure\Cookie;
use Zodream\Infrastructure\Request;
use Zodream\Infrastructure\Session;

class AccountController extends Controller {
	protected function rules() {
		return array(
			'logout' => '@',
			'*' => '?'
		);
	}

	function indexAction() {
		$this->show(array(
		));
	}

	function indexPost() {
		$result = EmpireForm::start()->login();
		EmpireModel::query()->addLoginLog(Request::post('email'), $result);
		if ($result) {
			Redirect::to('/');
		}
	}

	function registerAction() {
		$this->show();
	}

	function registerPost() {
		if (EmpireForm::start()->register()) {
			Redirect::to('/');
		}
	}

	function logoutAction() {
		EmpireModel::query('user')->updateById(Auth::user()['id'], array(
			'token' => null
		));
		Session::getInstance()->clear();
		Cookie::delete('token');
		Redirect::to('admin/account');
	}
}