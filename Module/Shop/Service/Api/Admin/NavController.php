<?php
namespace Module\Shop\Service\Api\Admin;

use Module\Shop\Domain\Models\NavigationModel;

class NavController extends Controller {

    public function indexAction() {
        $model_list = NavigationModel::all();
        return $this->renderData($model_list);
    }

    public function detailAction($id) {
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

    public function deleteAction($id) {
        NavigationModel::where('id', $id)->delete();
        return $this->renderData(true);
    }
}