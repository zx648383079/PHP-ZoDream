<?php
namespace Module\Note\Service;

use Module\ModuleController;
use Module\Note\Domain\Model\NoteModel;

class HomeController extends ModuleController {

    public $layout = 'main';
	
	public function indexAction() {
	    $model_list = NoteModel::with('user')->orderBy('id desc')->page();
		return $this->show(compact('model_list'));
	}
}