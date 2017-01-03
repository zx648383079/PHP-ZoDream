<?php
namespace Service\Admin;
use Domain\Model\Content\ModelFieldModel;
use Domain\Model\Content\ModelModel;
use Zodream\Domain\Response\Redirect;
use Zodream\Infrastructure\Http\Request;

/**
 * 动态表单生成
 */

class ContentController extends Controller {
	function indexAction() {
		$model = new ModelModel();
		$data = $model->findAll();
		$this->show(array(
			'title' => '模型列表',
			'data' => $data
		));
	}

	function addAction($id = null) {
		$model = new ModelModel();
		if (empty($id)) {
			$this->send('data', $model->findById($id));
		}
		$this->show(array(
			'title' => '模型添加'
		));
	}

	function addPost() {
		$model = new ModelModel();
		$result = $model->fill();
		if (!empty($result)) {
			Redirect::to('content');
		}
	}

	function fieldAction($modelid) {
		$model = new ModelFieldModel();
		$data = $model->findAll(array(
			'where' => 'model_id = '.intval($modelid)
		));
		$this->show(array(
			'title' => '字段列表',
			'data' => $data,
			'model' => $modelid
		));
	}

	function addFieldAction($modelid, $id = null) {
		$model = new ModelFieldModel();
		if (empty($id)) {
			$this->send('data', $model->findById($id));
		} else {
			$this->send('data', array(
				'model_id' => $modelid
			));
		}
		$this->show(array(
			'title' => '模型添加'
		));
	}

	/**
	 * @param $post Request\Post
	 */
	function addFieldPost($post) {
		$post->set('setting', serialize($post-get('setting')));
		$model = new ModelFieldModel();
		$result = $model->fill($post);
		if (!empty($result)) {
			Redirect::to('content/field');
		}
	}

	function formTypeAction($type) {
		$this->show(array(
			'type' => $type
		));
	}
}