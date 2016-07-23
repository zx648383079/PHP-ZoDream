<?php
namespace Service\Admin;

/**
 * 友情链接
 */

use Domain\Model\Home\FriendLinkModel;
use Zodream\Domain\Filter\DataFilter;
use Zodream\Domain\Response\Redirect;

class LinkController extends Controller {
	/**
	 * 友情链接
	 */
	function indexAction() {
		$data = FriendLinkModel::findAll();
		return $this->show(array(
			'data' => $data
		));
	}

	function addAction($id = null) {
		if (!empty($id)) {
			$this->send('data', EmpireModel::query('friendlink')->findById($id));
		}
		return $this->show();
	}

	function addPost() {
		$row = EmpireModel::query('friendlink')->save(array(
			'id' => '',
			'name' => 'required|string:1-100',
			'url' => 'required|url',
			'description' => '',
			'position' => 'int',
			'logo' => ''
		));
		if (!empty($row)) {
			Redirect::to('link');
		}
		$this->send('message', DataFilter::getError());
	}

	function deleteAction($id) {
		$this->delete('friendlink', $id);
	}
}