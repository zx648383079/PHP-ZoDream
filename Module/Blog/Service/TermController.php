<?php
namespace Module\Blog\Service;

use Module\Blog\Domain\Model\TermModel;
use Module\ModuleController;

class TermController extends ModuleController {
	protected $rules = array(
		'*' => '@'
	);
	
	public function indexAction() {
		$page = TermModel::findPage();
		return $this->show(array(
			'title' => '',
			'page' => $page
		));
	}

    public function addAction($id = null) {
        $model = $id > 0 ? TermModel::findOne($id) : new TermModel();
        if ($model->load() && $model->save()) {
            return $this->redirect(['Term']);
        }
        return $this->show([
            'model' => $model
        ]);
	}

    public function deleteAction($id) {
        TermModel::findOne($id)->delete();
        return $this->redirect(['Term']);
	}

    public function viewAction($id) {
		$model = TermModel::findOne($id);
        return $this->show([
            'model' => $model
        ]);
	}
}