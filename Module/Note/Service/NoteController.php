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

    public function saveAction() {
        $model = new NoteModel();
        $model->user_id = auth()->id();
        if ($model->load() && $model->save()) {
            return $this->renderData([
                'refresh' => true
            ]);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        NoteModel::where('id', $id)->delete();
        if (app('request')->isAjax()) {
            return $this->renderData();
        }
        return $this->redirect('./');
	}

}