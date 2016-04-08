<?php
namespace Domain\Form\Home;

use Zodream\Domain\Authentication\Auth;
use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\Home\BlogCategoryModel;
class BlogCategoryForm extends Form {
	public function get($id) {
		$model = new BlogCategoryModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::isPost()) {
			return ;
		}
		$data = Request::post('name,description');
		if (!$this->validate($data, array(
			'name' => 'required'
		))) {
			$this->send($data);
			$this->send('error', '验证失败！');
			return;
		}
		$data['user_id'] = Auth::user()['id'];
		$model = new BlogCategoryModel();
		$result = $model->add($data);
		if (empty($result)) {
			$this->send($data);
			$this->send('error', '服务器出错了！');
			return;
		}
	}
}