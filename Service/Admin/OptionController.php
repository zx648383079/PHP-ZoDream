<?php
namespace Service\Admin;

/**
 * 设置
 */
use Domain\Model\EmpireModel;
use Zodream\Infrastructure\Database\Command;
use Zodream\Infrastructure\Request\Post;

class OptionController extends Controller {
	function indexAction() {
		$data = EmpireModel::query('option')->findAll();
		$this->show(array(
			'data' => $data
		));
	}

	/**
	 * @param Post $post
	 */
	function indexPost($post) {
		if (!$post->has('name') || !$post->has('value')) {
			return;
		}
		Command::getInstance()->setTable('option')->insertOrUpdate(
			'name, value, autoload',
			':name, :value, :autoload',
			'value = :value, autoload = :autoload',
			array(
				':name' => $post->get('name'),
				':value' => $post['value'],
				':autoload' => $post->get('autoload', 'yes')
		));
	}
}