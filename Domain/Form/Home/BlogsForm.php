<?php
namespace Domain\Form\Home;

use Zodream\Domain\Authentication\Auth;
use Zodream\Domain\Form;
use Zodream\Domain\Response\Redirect;
use Zodream\Infrastructure\Request;
use Domain\Model\Home\BlogsModel;
class BlogsForm extends Form {
	public function get($id) {
		$model = new BlogsModel();
		$this->send('data', $model->findById($id));
	}
	
	public function fill() {
		$data = Request::post('title,image,keyword,description,content,category_id');
		if (!$this->validate($data, array(
			'title' => 'required',
			'content' => 'required',
			'category_id' => 'required'
		))) {
			$this->send($data);
			$this->send('error', '验证失败！');
			return;
		}
		$model = new BlogsModel();
		$result = $model->fill($data);
		if (empty($result)) {
			$this->send($data);
			$this->send('error', '服务器出错了！');
			return;
		}
		Redirect::to('blogs/view/id/'. $result);
	}
}