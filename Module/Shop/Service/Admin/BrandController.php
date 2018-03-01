<?php
namespace Module\Shop\Service\Admin;


use Module\Shop\Domain\Model\BrandModel;

class BrandController extends Controller {

    public function indexAction() {
        $model_list = BrandModel::page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethod('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = BrandModel::findOrNew($id);
        return $this->show(compact('model'));
    }

    public function saveAction() {
        $model = new BrandModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->redirectWithMessage($this->getUrl('brand'), '保存成功！');
        }
        return $this->redirectWithMessage($this->getUrl('brand'), $model->getFirstError());
    }

    public function deleteAction($id) {
        BrandModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('brand')
        ]);
    }
}