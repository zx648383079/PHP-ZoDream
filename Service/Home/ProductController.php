<?php
namespace Service\Home;

use Domain\Model\Home\ProductsModel;
class ProductController extends Controller {
	function indexAction() {
		$model = new ProductsModel();
		$this->show('products', array(
				'title' => '开源项目',
				'page' => $model->findPage()
		));
	}
}