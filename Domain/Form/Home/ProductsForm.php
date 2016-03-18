<?php
namespace Domain\Form\Home;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\Home\ProductsModel;
class ProductsForm extends Form {
	public function get($id) {
		$model = new ProductsModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('title,image,keyword,description,content,class_id,user_id,comment_count,status,allow_comment,template,update_at,create_at');
		if (!$this->validate($data, array(
			'title' => 'required',
			'image' => 'required',
			'keyword' => 'required',
			'description' => 'required',
			'content' => 'required',
			'class_id' => 'required',
			'user_id' => 'required',
			'comment_count' => 'required',
			'status' => 'required',
			'allow_comment' => 'required',
			'template' => 'required',
			'update_at' => 'required',
			'create_at' => 'required'
		))) {
			$this->send($data);
			$this->send('error', '验证失败！');
			return;
		}
		$model = new ProductsModel();
		$result = $model->add($data);
		if (empty($result)) {
			$this->send($data);
			$this->send('error', '服务器出错了！');
			return;
		}
	}
}