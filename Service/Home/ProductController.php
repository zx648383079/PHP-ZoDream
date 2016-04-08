<?php
namespace Service\Home;

use Domain\Form\Home\ProductCommentsForm;
use Domain\Model\Home\ProductClassesModel;
use Domain\Model\Home\ProductCommentsModel;
use Domain\Model\Home\ProductsModel;
use Zodream\Infrastructure\Request;

class ProductController extends Controller {
	function indexAction() {
		$model = new ProductsModel();
		$classes = new ProductClassesModel();
		$this->show('products', array(
			'title' => '开源项目',
			'page' => $model->findPage(Request::get('class')),
			'classes' => $classes->find()
		));
	}

	function viewAction($id = 0) {
		if (Request::isAjax()) {
			$this->comments($id);
		}
		$model = new ProductsModel();
		$product = $model->findView($id);
		$this->show('single', array(
			'title' => $product['title'],
			'data' => $product,
			'links' => $model->getNextAndBefore($id)
		));
	}

	function comments($id) {
		$form = new ProductCommentsForm();
		$form->runAction('add');
		$page = Request::get('comments');
		$model = new ProductCommentsModel();
		$this->ajaxReturn($model->findPage($id, $page));
	}
}