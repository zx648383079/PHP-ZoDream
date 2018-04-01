<?php
namespace Module\Book\Service\Admin;


use Module\Book\Domain\Model\BookCategoryModel;

class CategoryController extends Controller {
    public function indexAction($keywords = null) {
        $model_list = BookCategoryModel::withCount('book')->when(!empty($keywords), function ($query) {
            BookCategoryModel::search($query, 'name');
        })->all();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethod('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = BookCategoryModel::findOrNew($id);
        return $this->show(compact('model'));
    }

    public function saveAction() {
        $model = new BookCategoryModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('category')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        BookCategoryModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('category')
        ]);
    }
}