<?php
namespace Service\Admin;
/**
 * 废料科普
 */

use Zodream\Domain\Response\Redirect;
use Zodream\Infrastructure\Request\Post;

class CompanyController extends Controller {
	function indexAction() {
		$page = EmpireModel::query('company')->getPage(null, 'id,name,charge,update_at');
		$this->show(array(
			'title' => '公司管理',
			'page' => $page
		));
	}

	function addAction($id = null) {
		if (!empty($id)) {
			$this->send('data', EmpireModel::query('company')->findOne($id));
		}
		$this->show(array(
			'title' => '新增公司'
		));
	}

	/**
	 * @param Post $post
	 */
	function addPost($post) {
		$result = EmpireModel::query('company')->save(array(
			'id' => 'int',
			'name' => 'required|string:3-50',
			'description' => '',
			'charge' => 'required|string3-100',
			'phone' => 'required|string3-100',
			'create_at' => '',
			'update_at' => ''
		), $post->get());
		if (empty($result)) {
			$this->send('error', '验证失败！');
			return;
		}
		Redirect::to(['company']);
	}

	function deleteAction($id) {
		$this->delete('company', $id);
	}

	function viewAction($id) {
		$data = EmpireModel::query('company')->findOne($id);
		$this->show(array(
			'title' => '查看 '.$data['name'],
			'data' => $data
		));
	}
}