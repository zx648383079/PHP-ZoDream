<?php
namespace Module\Address\Service;

use Module\Address\Domain\Model\AddressModel;
use Module\ModuleController;

class HomeController extends ModuleController {
	protected $rules = array(
		'*' => '*'
	);
	
	public function indexAction() {
		$page = AddressModel::findPage();
		return $this->show(array(
			'title' => '',
			'page' => $page
		));
	}

    public function addAction($id = null) {
        $model = $id > 0 ? AddressModel::findOne($id) : new AddressModel();
        if ($model->load() && $model->save()) {
            return $this->redirect(['Address']);
        }
        return $this->show([
            'model' => $model
        ]);
	}

    public function deleteAction($id) {
        AddressModel::findOne($id)->delete();
        return $this->redirect(['Address']);
	}

    public function viewAction($id) {
		$model = AddressModel::findOne($id);
        return $this->show([
            'model' => $model
        ]);
	}
}