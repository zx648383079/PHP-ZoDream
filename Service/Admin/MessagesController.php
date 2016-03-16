<?php
namespace Service\Admin;

use Domain\Model\MessagesModel;
class MessagesController extends Controller {
	protected $rules = array(
			'*' => '@'
	);
	
	function indexAction($page = 0) {
		$model = new MessagesModel();
		$this->show(array(
				'messages' => $model->findPage($page)
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