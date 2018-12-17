<?php
namespace Module\Note\Service;

use Module\Note\Domain\Model\NoteModel;
use Module\ModuleController;

class NoteController extends ModuleController {

	protected $rules = array(
		'*' => '@'
	);
	
	public function indexAction() {

	}

    public function deleteAction($id) {
        NoteModel::where('id', $id)->delete();
        return $this->redirect('./');
	}

}