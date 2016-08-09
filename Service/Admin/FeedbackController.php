<?php
namespace Service\Admin;

/**
 * 随想
 */
use Domain\Model\FeedbackModel;

class FeedbackController extends Controller {
	function indexAction() {
		$page = FeedbackModel::find()->order('create_at desc')->page();
		return $this->show(array(
			'page' => $page
		));
	}
	
	function viewAction($id) {
		$model = FeedbackModel::findOne($id);
		$model->read = true;
		return $this->show([
			'title' => '查看反馈',
			'data' => $model
		]);
	}
	
	function deleteAction($id) {
		FeedbackModel::findOne($id)->delete();
		return $this->redirect(['feedback']);
	}
}