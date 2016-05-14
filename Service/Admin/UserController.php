<?php
namespace Service\Admin;

/**
 * 用户信息
 */
use Domain\Form\EmpireForm;
use Domain\Model\EmpireModel;

use Zodream\Domain\Authentication\Auth;
use Zodream\Domain\Response\Redirect;
use Zodream\Infrastructure\Log;
use Zodream\Infrastructure\Request;

class UserController extends Controller {
	protected function rules() {
		return array(
			'loginLog' => '@',
			'index' => '@',
			'*' => 'admin'
		);
	}
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
			Redirect::to('account/logout', 2, '修改成功！请重新登陆!');
		}
	}

	function userAction() {
		$name = Request::get('name');
		$where = array();
		if (!empty($name) && '*' != $name) {
			$where['name'] = array(
				'in',
				explode(',', $name)
			);
		}
		$page = EmpireModel::query('user')->getPage(array(
			'where' => $where
		), 'id,name,email,login_num,update_ip,update_at');
		$this->show(array(
			'page' => $page
		));
	}

	function addUserAction($id = null) {
		$data = EmpireModel::query('role')->findAll();
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
			Redirect::to('user/user');
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
		$data = EmpireModel::query('authorization')->findAll();
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
			Redirect::to('user/role');
		}
	}

	function logAction() {
		$page = EmpireModel::query('log')->getPage();
		$this->show(array(
			'page' => $page
		));
	}

	function loginLogAction() {
		$page = EmpireModel::query('login_log')->getPage(array(
			'where' => array('user' => Auth::user()['email']),
			'order' => 'create_at desc'
		));
		$this->show(array(
			'page' => $page
		));
	}
}