<?php
namespace Service\Home;

use Domain\Model\PostsModel;
class ProductController extends Controller {
	function indexAction() {
		$model = new PostsModel();
		$this->show('products', array(
				'title' => '我们的产品',
				'products' => $model->findProducts()
		));
	}
}