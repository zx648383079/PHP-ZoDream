<?php
namespace Service\Admin;

use Domain\Form\Home\OptionsForm;
class SettingsController extends Controller {
	protected $rules = array(
			'*' => '@'
	);
	
	function indexAction() {
		$form = new OptionsForm();
		$form->set();
		$form->get();
		$this->show();
	}
	
	function infoAction() {
		$this->show();
	}
}