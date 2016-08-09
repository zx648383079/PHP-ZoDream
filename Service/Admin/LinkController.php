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
		$model = empty($id) ? new FriendLinkModel() : FriendLinkModel::findOne($id);
		if ($model->load() && $model->save()) {
			return $this->redirect('link');
		}
		return $this->show([
			'data' => $model
		]);
	}

	function deleteAction($id) {
		FriendLinkModel::findOne($id)->delete();
		return $this->redirect(['link']);
	}
}