<?php
namespace Domain\Form\Home;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\Home\CommentsModel;
class CommentsForm extends Form {
	public function get($id) {
		$model = new CommentsModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('content,name,email,ip,user_id,post_id,parent_id,create_at');
		if (!$this->validate($data, array(
			'content' => 'required',
			'name' => 'required',
			'email' => 'required',
			'ip' => 'required',
			'user_id' => 'required',
			'post_id' => 'required',
			'parent_id' => 'required',
			'create_at' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$model = new CommentsModel();
		$model->add($data);
	}
}