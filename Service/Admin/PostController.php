<?php
namespace Service\Admin;

/**
 * 博客
 */
use Domain\Model\EmpireModel;
use Zodream\Infrastructure\ObjectExpand\PinYin;
use Zodream\Infrastructure\Request\Post;

class PostController extends Controller {

	function indexAction() {
		$data = EmpireModel::query('post')->getPage();
		$this->show(array(
			'page' => $data
		));
	}

	function addAction() {
		$this->show();
	}

	function termAction() {
		$data = EmpireModel::query('term')->find();
		$this->show(array(
			'data' => $data
		));
	}

	/**
	 * @param Post $post
	 */
	function termPost($post) {
		$data = $post->get('id,name');
		if (empty($data['name'])) {
			return;
		}
		if (!empty($data['id'])) {
			EmpireModel::query('term')->updateById($data['id'], array(
				'name' => $data['name']
			));
			return;
		}
		EmpireModel::query('term')->add(array(
			'name' => $data['name'],
			'slug' => PinYin::encode($data['name'], 'all')
		));
	}

	function commentAction() {
		$data = EmpireModel::query('comment')->getPage();
		$this->show(array(
			'page' => $data
		));
	}
}