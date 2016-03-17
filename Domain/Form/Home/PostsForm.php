<?php
namespace Domain\Form\Home;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\Home\PostsModel;
class PostsForm extends Form {
	public function get($id) {
		$model = new PostsModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('title,image,keyword,description,content,class_id,user_id,kind,comment_count,template,update_at,create_at');
		if (!$this->validate($data, array(
			'title' => 'required',
			'image' => 'required',
			'keyword' => 'required',
			'description' => 'required',
			'content' => 'required',
			'class_id' => 'required',
			'user_id' => 'required',
			'kind' => 'required',
			'comment_count' => 'required',
			'template' => 'required',
			'update_at' => 'required',
			'create_at' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$model = new PostsModel();
		$model->add($data);
	}
}