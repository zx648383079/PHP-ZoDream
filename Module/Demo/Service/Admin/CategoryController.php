<?php
namespace Module\Demo\Service\Admin;

use Module\Demo\Domain\Model\CategoryModel;

class CategoryController extends Controller {

    public function indexAction($keywords = null) {
        $items = CategoryModel::withCount('post')->orderBy('id', 'desc')->all();
        return $this->show(compact('items'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = CategoryModel::findOrNew($id);
        return $this->show(compact('model'));
    }

    public function saveAction() {
        $model = new CategoryModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('category')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        CategoryModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('category')
        ]);
    }
}