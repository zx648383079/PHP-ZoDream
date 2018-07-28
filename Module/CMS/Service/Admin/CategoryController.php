<?php
namespace Module\CMS\Service\Admin;

use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\ModelModel;

class CategoryController extends Controller {
    public function indexAction() {
        $model_list = CategoryModel::page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = CategoryModel::findOrNew($id);
        $model_list = ModelModel::select('name', 'id')->all();
        return $this->show(compact('model', 'model_list'));
    }

    public function saveAction() {
        $model = new CategoryModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        return $this->jsonSuccess([
            'url' => $this->getUrl('category')
        ]);
    }

    public function deleteAction($id) {
        CategoryModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('category')
        ]);
    }
}