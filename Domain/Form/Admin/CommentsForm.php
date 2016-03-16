<?php
namespace Domain\Form\Admin;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\Admin\CommentsModel;
class CommentsForm extends Form {
	public function get($id) {
		$model = new CommentsModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('content,ip,name,email,user_id,posts_id,parent_id,cdate');
		if (!$this->validate($data, array(
			'content' => 'required',
			'ip' => 'required',
			'name' => 'required',
			'email' => 'required',
			'user_id' => 'required',
			'posts_id' => 'required',
			'parent_id' => 'required',
			'cdate' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$model = new CommentsModel();
		$model->add($data);
	}
}