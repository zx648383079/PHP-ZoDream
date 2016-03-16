<?php
namespace Domain\Form;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\MessagesModel;
class MessageForm extends Form {
	public function set() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('name,email,title,content');
		if (!$this->validate($data, array(
				'name' => 'string:0-20',
				'email' => 'required|email',
				'title' => 'string:0-40',
				'content' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$data['ip'] = Request::ip();
		$data['cdate'] = time();
		$model = new MessagesModel();
		$model->add($data);
	}
	
	public function get($id) {
		$model = new MessagesModel();
		$this->send('data', $model->findById($id));
	}
}