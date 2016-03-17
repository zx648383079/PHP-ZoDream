<?php
namespace Service\Admin;

use Domain\Model\Home\MessagesModel;
class MessagesController extends Controller {
	protected $rules = array(
			'*' => '@'
	);
	
	function indexAction() {
		$model = new MessagesModel();
		$this->show(array(
			'title' => '消息',
			'page' => $model->findPage()
		));
	}
	
	function viewAction($id = 0) {
		$model = new MessagesModel();
		$this->send('message', $model->findById($id));
		$this->show();
	}
	
	function chatAction() {
		$this->show();
	}
}