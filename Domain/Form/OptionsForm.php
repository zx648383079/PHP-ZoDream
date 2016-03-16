<?php
namespace Domain\Form;

use Zodream\Domain\Form;
use Zodream\Infrastructure\Request;
use Domain\Model\OptionsModel;
use Zodream\Domain\Response\Redirect;
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
			'about' => '',
			'contact' => ''
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
		if (!Request::getInstance()->isPost()) {
			$this->get();
			return ;
		}
		$data = Request::getInstance()->post('title,tagline,keywords,description,author');
		$this->send('options', $data);
		$model = new OptionsModel();
		foreach ($data as $key => $value) {
			$model->update(array(
					'value' => $value
			), "name = '{$key}'" );
		}
	}
	
	public function addIndex() {
		if (!Request::getInstance()->isPost()) {
			return ;
		}
		$value = Request::getInstance()->post('value');
		$model = new OptionsModel();
		$id = $model->add(array(
				'name' => 'index',
				'value' => $value
		));
		Redirect::to('singles/view/id/'.$id);
	}
	
	public function setPage($id) {
		$model = new OptionsModel();
		if (!Request::getInstance()->isPost()) {
			$this->send('single', $model->findById($id));
			return ;
		}
		$value = Request::getInstance()->post('value');
		$model->updateById($id, array(
				'value' => $value
		));
		Redirect::to('singles/view/id/'.$id);
	}
}