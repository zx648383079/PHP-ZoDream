<?php
namespace Service\Home;

use Domain\Model\Home\BlogsModel;
use Domain\Model\Home\OptionsModel;
use Domain\Model\Home\ProductsModel;

class HomeController extends Controller {
	function indexAction() {
		$product = new ProductsModel();
		$blog = new BlogsModel();
		$option = new OptionsModel();
		$option->findPage();
		$this->show('index', array(
			'title' => '首页',
			'options' => $option->findPage(),
			'product' => $product->getNew(),
			'hotproducts' => $product->getHot(),
			'hotblogs' => $blog->getHot()
		));
	}
}