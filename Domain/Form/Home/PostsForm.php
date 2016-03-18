<?php
namespace Domain\Form\Home;

use Zodream\Domain\Authentication\Auth;
use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\Home\PostsModel;
class PostsForm extends Form {
	public function get($id) {
		$model = new PostsModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set($id = null) {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('title,image,keyword,description,content,class_id,kind');
		if (!$this->validate($data, array(
			'title' => 'required',
			'content' => 'required',
			'class_id' => 'required',
			'kind' => 'required'
		))) {
			$this->send($data);
			return;
		}
		$model = new PostsModel();
		if (empty($id)) {
			$data['user_id'] = Auth::user()['id'];
			$data['create_at'] = time();
			$model->add($data);
			return;
		}
		$data['update_at'] = time();
		$model->updateById($id, $data);
	}
}