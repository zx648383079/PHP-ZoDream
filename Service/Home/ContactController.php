<?php
namespace Service\Home;

use Domain\Form\MessageForm;
use Domain\Model\OptionsModel;
class ContactController extends Controller {
	function indexAction() {
		$form = new MessageForm();
		$form->set();
		$model = new OptionsModel();
		$this->show('contact', array(
				'title' => '联系我们',
				'data' => $model->findPage('contact')
		));
	}
}