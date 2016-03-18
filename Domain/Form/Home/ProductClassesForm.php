<?php
namespace Domain\Form\Home;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\Home\ProductClassesModel;
class ProductClassesForm extends Form {
	public function get($id) {
		$model = new ProductClassesModel();
		$this->send('data', $model->findById($id));
	}
	
	public function set() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$data = Request::getInstance()->post('name,description,status');
		if (!$this->validate($data, array(
			'name' => 'required',
			'description' => 'required',
			'status' => 'required'
		))) {
			$this->send($data);
			$this->send('error', '验证失败！');
			return;
		}
		$model = new ProductClassesModel();
		$result = $model->add($data);
		if (empty($result)) {
			$this->send($data);
			$this->send('error', '服务器出错了！');
			return;
		}
	}
}