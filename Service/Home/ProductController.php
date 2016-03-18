<?php
namespace Service\Home;

use Domain\Model\Home\ProductsModel;
class ProductController extends Controller {
	function indexAction() {
		$model = new ProductsModel();
		$this->show('products', array(
				'title' => '我们的产品',
				'page' => $model->findPage()
		));
	}
}