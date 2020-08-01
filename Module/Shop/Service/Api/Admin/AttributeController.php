<?php
namespace Module\Shop\Service\Api\Admin;


use Module\Shop\Domain\Models\AttributeGroupModel;
use Module\Shop\Domain\Models\AttributeModel;

class AttributeController extends Controller {

    public function indexAction($group_id) {
        $model_list = AttributeModel::with('group')->where('group_id', $group_id)->page();
        return $this->renderPage($model_list);
    }

    public function detailAction($id) {
        $model = AttributeModel::find($id);
        return $this->render($model);
    }

    public function saveAction() {
        $model = new AttributeModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->render($model);
        }
        return $this->renderFailure($model->getFirstError());
    }


    public function groupAction() {
        $model_list = AttributeGroupModel::all();
        return $this->renderData($model_list);
    }

    public function detailGroupAction($id) {
        $model = AttributeGroupModel::find($id);
        return $this->render($model);
    }

    public function saveGroupAction() {
        $model = new AttributeGroupModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->render($model);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteCategoryAction($id) {
        AttributeGroupModel::where('id', $id)->delete();
        return $this->renderData(true);
    }
}