<?php
namespace Service\Home;

use Domain\Model\Home\OptionsModel;
class AboutController extends Controller {
	function indexAction() {
		$model = new OptionsModel();
		$this->show('about', array(
				'title' => '关于我们',
				'data' => $model->findPage('about'),
		));
	}
}