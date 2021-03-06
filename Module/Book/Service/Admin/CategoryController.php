<?php
namespace Module\Book\Service\Admin;


use Domain\Model\SearchModel;
use Module\Book\Domain\Model\BookCategoryModel;

class CategoryController extends Controller {
    public function indexAction($keywords = null) {
        $model_list = BookCategoryModel::withCount('book')->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'name');
        })->all();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction($id) {
        $model = BookCategoryModel::findOrNew($id);
        return $this->show('edit', compact('model'));
    }

    public function saveAction() {
        $model = new BookCategoryModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->renderData([
                'url' => $this->getUrl('category')
            ]);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        BookCategoryModel::where('id', $id)->delete();
        return $this->renderData([
            'url' => $this->getUrl('category')
        ]);
    }
}