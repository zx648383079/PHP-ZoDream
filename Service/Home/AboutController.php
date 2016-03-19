<?php
namespace Service\Home;

use Domain\Form\Home\MessagesForm;
use Domain\Model\Home\OptionsModel;
class AboutController extends Controller {
	function indexAction() {
		$model = new OptionsModel();
		$form = new MessagesForm();
		$form->runAction('add');
		$this->show('about', array(
				'title' => '关于我们',
				'data' => $model->findPage('about'),
		));
	}
}