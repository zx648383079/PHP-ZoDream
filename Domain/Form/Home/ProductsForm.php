<?php
namespace Domain\Form\Home;

use Zodream\Domain\Form;
use Zodream\Domain\Response\Redirect;
use Zodream\Infrastructure\Request;
use Domain\Model\Home\ProductsModel;
class ProductsForm extends Form {
	public function get($id) {
		$model = new ProductsModel();
		$this->send('data', $model->findById($id));
	}
	
	public function fill() {
		$data = Request::post('title,image,keyword,description,content,class_id');
		if (!$this->validate($data, array(
			'title' => 'required',
			'content' => 'required',
			'class_id' => 'required'
		))) {
			$this->send($data);
			$this->sendMessage(1);
			return;
		}
		$model = new ProductsModel();
		$result = $model->fill($data);
		if (empty($result)) {
			$this->send($data);
			$this->sendMessage(2);
			return;
		}
		Redirect::to('products/view/id/'. $result);
	}
}