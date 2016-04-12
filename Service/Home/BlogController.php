<?php
namespace Service\Home;

use Domain\Model\EmpireModel;

class BlogController extends Controller {
	function indexAction() {
		$data = EmpireModel::query('post')->getPage();
		$term = EmpireModel::query('term')->find();
		$this->show(array(
			'title' => '博客',
			'data' => $data,
			'term' => $term
		));
	}
}