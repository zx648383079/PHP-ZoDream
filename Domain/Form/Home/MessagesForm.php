<?php
namespace Domain\Form\Home;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\Home\MessagesModel;
class MessagesForm extends Form {
	public function get($id) {
		$model = new MessagesModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('name,email,title,content,ip,readed,create_at');
		if (!$this->validate($data, array(
			'name' => 'required',
			'email' => 'required',
			'title' => 'required',
			'content' => 'required',
			'ip' => 'required',
			'readed' => 'required',
			'create_at' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$model = new MessagesModel();
		$model->add($data);
	}
}