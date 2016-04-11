<?php
namespace Service\Empire\Admin;

use Domain\Form\EmpireForm;
use Domain\Model\EmpireModel;
use Service\Empire\Controller;
use Zodream\Domain\Authentication\Auth;
use Zodream\Domain\Response\Redirect;
use Zodream\Infrastructure\Cookie;
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
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
		$time = TimeExpand::getBeginAndEndTime(TimeExpand::TODAY);
		$num = EmpireModel::query('login_log')->count(array(
			'ip' => Request::ip(),
			'status = 0',
			 array(
				 'create_at', 'between', $time[0], $time[1]
			)
		));
		if ($num > 2) {
			$num = intval($num / 3);
			$this->send('code', $num);
			Session::setValue('level', $num);
		}
		$this->show(array(
		));
	}

	function indexPost() {
		$code = Session::getValue('code');
		if (!empty($code) && strtolower($code) !== strtolower(Request::post('code'))) {
			return;
		}
		$result = EmpireForm::start()->login();
		EmpireModel::query()->addLoginLog(Request::post('email'), $result);
		if ($result) {
			Redirect::to('admin/admin');
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