<?php
namespace Domain\Form\Home;

use Zodream\Domain\Form;
use Zodream\Domain\Response\Redirect;
use Zodream\Infrastructure\Request;
use Domain\Model\Home\OptionsModel;

class OptionsForm extends Form {
	public function get() {
		$model = new OptionsModel();
		$results = $model->findValue();
		if (empty($results)) {
			$results = $this->init();
		}
		$this->send('options', $results);
	}

	public function init() {
		$data = array(
			'title' => 'ZoDream',
			'tagline' => 'ZoDream',
			'keywords' => 'ZoDream',
			'description' => 'ZoDream',
			'author' => 'ZoDream',
			'index' => '',
			'about' => ''
		);
		$model = new OptionsModel();
		foreach ($data as $key => $value) {
			$model->add(array(
				'name' => $key,
				'value' => $value
			));
		}
		return $data;
	}

	public function set() {
		if (!Request::isPost()) {
			$this->get();
			return ;
		}
		$data = Request::post('title,tagline,keywords,description,author');
		$this->send('options', $data);
		$model = new OptionsModel();
		foreach ($data as $key => $value) {
			$model->update(array(
				'value' => $value
			), "name = '{$key}'" );
		}
	}

	public function addIndex() {
		if (!Request::isPost()) {
			return ;
		}
		$value = Request::post('value');
		$model = new OptionsModel();
		$id = $model->add(array(
			'name' => 'index',
			'value' => $value
		));
		Redirect::to('singles/view/id/'.$id);
	}

	public function setPage($id) {
		$model = new OptionsModel();
		if (!Request::isPost()) {
			$this->send('single', $model->findById($id));
			return ;
		}
		$value = Request::post('value');
		$model->updateById($id, array(
			'value' => $value
		));
		Redirect::to('singles/view/id/'.$id);
	}
}