<?php
namespace Domain\Form;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\PostsModel;
use Zodream\Domain\Response\Redirect;
use Zodream\Domain\Authentication\Auth;
class PostsForm extends Form {
	public function get($id) {
		$model = new PostsModel();
		$this->send('post', $model->findById($id));
	}
	
	public function set($id = null) {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('title,content,kind');
		if (!$this->validate($data, array(
			'title' => 'required|string:0-45',
			'content' => 'required',
			'kind' => 'required|int'
		))) {
			$this->send($data);
			return;
		}
		$model = new PostsModel();
		if (is_null($id)) {
			$data['user_id'] = Auth::user();
			$data['udate'] = $data['cdate'] = time();
			$id = $model->add($data);
		} else {
			$data['udate'] = time();
			$model->updateById($id, $data);
		}
		Redirect::to('posts/view/id/'.$id);
	}
}