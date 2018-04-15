<?php
namespace Module\Shop\Service\Admin;


use Module\Shop\Domain\Model\CategoryModel;

class CategoryController extends Controller {

    public function indexAction() {
        $model_list = CategoryModel::page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethod('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = CategoryModel::findOrNew($id);
        $cat_list = CategoryModel::where('id', '!=', $id)->all();
        return $this->show(compact('model', 'cat_list'));
    }

    public function saveAction() {
        $model = new CategoryModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->redirectWithMessage($this->getUrl('category'), '保存成功！');
        }
        return $this->redirectWithMessage($this->getUrl('category'), $model->getFirstError());
    }

    public function deleteAction($id) {
        CategoryModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('category')
        ]);
    }
}