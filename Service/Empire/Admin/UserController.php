<?php
namespace Service\Empire\Admin;

use Domain\Form\EmpireForm;
use Domain\Model\EmpireModel;
use Service\Empire\Controller;
use Zodream\Domain\Authentication\Auth;
use Zodream\Domain\Response\Redirect;
use Zodream\Infrastructure\Log;
use Zodream\Infrastructure\Request;

class UserController extends Controller {
	protected $rules = array(
		'*' => '@'
	);
	/*
	 * 增加信息
	 */
	function indexAction() {
		$this->show(array(
			'name' => Auth::user()['name']
		));
	}

	function indexPost() {
		$result = EmpireForm::start()->resetPassword();
		Log::save($result, 'resetPassword');
		if ($result) {
			Redirect::to('admin/account/logout', 2, '修改成功！请重新登陆!');
		}
	}

	function userAction() {
		$page = EmpireModel::query('user')->getPage(null, 'id,name,email,login_num,update_ip,update_at');
		$this->show(array(
			'page' => $page
		));
	}

	function addUserAction($id = null) {
		$data = EmpireModel::query('role')->find();
		if (!empty($id)) {
			$result = EmpireModel::query('user')->findById($id, 'id,name,email');
			if (!empty($result)) {
				$this->send($result);
				$this->send('role', EmpireModel::query('role_user')->findOne('user_id = '.intval($id)));
			}
		}
		$this->show(array(
			'data' => $data
		));
	}

	function addUserPost() {
		$result = EmpireForm::start()->addUser();
		if ($result) {
			Redirect::to('admin/user/user');
		}
	}

	function authorizationAction() {
		$data = EmpireModel::query('authorization')->select('order by id desc');
		$this->show(array(
			'data' => $data
		));
	}

	function authorizationPost() {
		EmpireModel::query('authorization')->save(array(
			'id' => '',
			'name' => 'required'
		));
	}

	function roleAction() {
		$data = EmpireModel::query('role')->select('order by id desc');
		$this->show(array(
			'data' => $data
		));
	}

	function addRoleAction($id = null) {
		$data = EmpireModel::query('authorization')->find();
		if (!empty($id)) {
			$result = EmpireModel::query('role')->findById($id, 'name');
			if (!empty($result)) {
				$this->send('name', $result['name']);
				$this->send('roles', EmpireModel::query()->getAuthByRole($id));
			}
		}
		$this->show(array(
			'data' => $data,
			'id' => $id
		));
	}

	function addRolePost() {
		$result = EmpireForm::start()->addRole();
		if ($result) {
			Redirect::to('admin/user/role');
		}
	}

	function logAction() {
		$page = EmpireModel::query('log')->getPage();
		$this->show(array(
			'page' => $page
		));
	}

	function loginlogAction() {
		$page = EmpireModel::query('login_log')->getPage();
		$this->show(array(
			'page' => $page
		));
	}
}