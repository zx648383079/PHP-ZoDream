<?php
namespace Module\Shop\Service\Api\Admin;

use Module\Shop\Domain\Models\NavigationModel;

class NavController extends Controller {

    public function indexAction() {
        return $this->renderData(NavigationModel::all());
    }

    public function detailAction(int $id) {
        $model = NavigationModel::find($id);
        return $this->render($model);
    }

    public function saveAction() {
        $model = new NavigationModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->render($model);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteAction(int $id) {
        NavigationModel::where('id', $id)->delete();
        return $this->renderData(true);
    }
}