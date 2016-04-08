<?php
namespace Domain\Form\Home;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\Home\MessagesModel;
class MessagesForm extends Form {
	public function add() {
		$data = Request::post('name,email,title,content');
		if (!$this->validate($data, array(
			'name' => 'required',
			'email' => 'required',
			'title' => 'required',
			'content' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$data['ip'] = Request::ip();
		$model = new MessagesModel();
		$model->fill($data);
	}
}