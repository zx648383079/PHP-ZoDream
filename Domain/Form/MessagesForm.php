<?php
namespace Domain\Form;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\MessagesModel;
class MessagesForm extends Form {
	public function get($id) {
		$model = new MessagesModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('name,email,title,content,ip,readed,cdate');
		if (!$this->validate($data, array(
			'name' => 'required',
			'email' => 'required',
			'title' => 'required',
			'content' => 'required',
			'ip' => 'required',
			'readed' => 'required',
			'cdate' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$model = new MessagesModel();
		$model->add($data);
	}
}