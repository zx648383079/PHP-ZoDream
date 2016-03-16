<?php
namespace Domain\Form\WeChat;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\WeChat\MessagesModel;
class MessagesForm extends Form {
	public function get($id) {
		$model = new MessagesModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('account_id,fans_id,sent_at,resource_id,reply_id,content,msg_id,created_at,updated_at');
		if (!$this->validate($data, array(
			'account_id' => 'required',
			'fans_id' => 'required',
			'sent_at' => 'required',
			'resource_id' => 'required',
			'reply_id' => 'required',
			'content' => 'required',
			'msg_id' => 'required',
			'created_at' => 'required',
			'updated_at' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$model = new MessagesModel();
		$model->add($data);
	}
}