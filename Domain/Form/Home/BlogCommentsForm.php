<?php
namespace Domain\Form\Home;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\Home\BlogCommentsModel;
class BlogCommentsForm extends Form {
	public function add() {
		$data = Request::post('content,name,email,blog_id,parent_id');
		if (!$this->validate($data, array(
			'content' => 'required',
			'name' => 'required',
			'email' => 'required|email'
		))) {
			$this->ajaxReturn(array(
				'status' => 'failure',
				'error' =>  '验证失败！'
			));
			return;
		}
		$model = new BlogCommentsModel();
		$result = $model->fill($data);
		if (empty($result)) {
			$this->ajaxReturn(array(
				'status' => 'failure',
				'error' =>  '服务器出错了！'
			));
			return;
		}
		$this->ajaxReturn(array(
			'status' => 'success'
		));
	}
}