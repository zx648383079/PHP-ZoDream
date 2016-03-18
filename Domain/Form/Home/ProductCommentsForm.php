<?php
namespace Domain\Form\Home;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\Home\ProductCommentsModel;
class ProductCommentsForm extends Form {
	public function get($id) {
		$model = new ProductCommentsModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('content,name,email,ip,user_id,product_id,parent_id,create_at');
		if (!$this->validate($data, array(
			'content' => 'required',
			'name' => 'required',
			'email' => 'required',
			'ip' => 'required',
			'user_id' => 'required',
			'product_id' => 'required',
			'parent_id' => 'required',
			'create_at' => 'required'
		))) {
			$this->send($data);
			$this->send('error', '验证失败！');
			return;
		}
		$model = new ProductCommentsModel();
		$result = $model->add($data);
		if (empty($result)) {
			$this->send($data);
			$this->send('error', '服务器出错了！');
			return;
		}
	}
}