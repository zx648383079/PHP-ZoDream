<?php
namespace Service\Admin;

/**
 * 随想
 */
use Domain\Model\EmpireModel;

class FeedbackController extends Controller {
	function indexAction() {
		$page = EmpireModel::query('feedback')->getPage('order by create_at desc');
		$this->show(array(
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