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
		$data = EmpireModel::query('feedback')->findById($id);
		if ($data['read'] == 0) {
			EmpireModel::query('feedback')->updateById($id, ['read' => 1]);
			$data['read'] = 1;
		}
		$this->show([
			'title' => '查看反馈',
			'data' => $data
		]);
	}
	
	function deleteAction($id) {
		$this->delete('feedback', $id);
	}
}