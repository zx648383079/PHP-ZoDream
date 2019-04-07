<?php
namespace Module\Shop\Service\Admin;

use Module\Shop\Domain\Models\NavigationModel;

class NavController extends Controller {

    public function indexAction() {
        $model_list = NavigationModel::all();
        $type_list = NavigationModel::$type_list;
        return $this->show(compact('model_list', 'type_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = NavigationModel::findOrNew($id);
        $type_list = NavigationModel::$type_list;
        return $this->show(compact('model', 'type_list'));
    }

    public function saveAction() {
        $model = new NavigationModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->redirectWithMessage($this->getUrl('nav'), '保存成功！');
        }
        return $this->redirectWithMessage($this->getUrl('nav'), $model->getFirstError());
    }

    public function deleteAction($id) {
        NavigationModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('nav')
        ]);
    }
}