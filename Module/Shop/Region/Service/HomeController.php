<?php
namespace Module\Shop\Region\Service;

use Module\ModuleController;
use Module\Region\Domain\Model\RegionModel;

class HomeController extends ModuleController {

    public function indexAction() {

    }

    public function saveAction() {
        $model = new RegionModel();
        if ($model->load() && $model->save()) {
            return $this->jsonSuccess($model);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        RegionModel::findOne($id)->delete();
        return $this->jsonSuccess();
    }

    public function childAction($id) {
        return $this->jsonSuccess(RegionModel::findAll(['parent_id' =>$id]));
    }

    public function allAction() {
        return $this->jsonSuccess(RegionModel::tree());
    }
}