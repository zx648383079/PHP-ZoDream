<?php
namespace Domain\Form;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\CommentsModel;
use Zodream\Infrastructure\ObjectExpand\StringExpand;
use Domain\Model\PostsModel;
class CommentsForm extends Form {
	public function get($id) {
		$model = new CommentsModel();
		$this->send('comments', $model->findByPost($id));
	}
	
	public function set($post_id) {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('content,name,email');
		if (!$this->validate($data, array(
			'content' => 'required',
			'name' => 'required',
			'email' => 'email|required'
		))) {
			$this->send($data);
			return;
		}
		$data['content'] = StringExpand::filterHtml($data['content']);
		$data['posts_id'] = $post_id;
		$data['ip'] = Request::ip();
		$data['cdate'] = time();
		$model = new CommentsModel();
		if ($model->add($data)) {
			$posts = new PostsModel();
			$posts->updateOne('comment_count', 'id = '. $post_id);
		}
	}
}