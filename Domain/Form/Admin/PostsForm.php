<?php
namespace Domain\Form\Admin;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\Admin\PostsModel;
class PostsForm extends Form {
	public function get($id) {
		$model = new PostsModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('title,content,kind,comment_count,user_id,premalink,udate,cdate');
		if (!$this->validate($data, array(
			'title' => 'required',
			'content' => 'required',
			'kind' => 'required',
			'comment_count' => 'required',
			'user_id' => 'required',
			'premalink' => 'required',
			'udate' => 'required',
			'cdate' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$model = new PostsModel();
		$model->add($data);
	}
}