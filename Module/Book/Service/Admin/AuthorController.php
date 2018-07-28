<?php
namespace Module\Book\Service\Admin;


use Module\Book\Domain\Model\BookAuthorModel;

class AuthorController extends Controller {
    public function indexAction($keywords = null) {
        $model_list = BookAuthorModel::when(!empty($keywords), function ($query) {
            BookAuthorModel::search($query, 'name');
        })->page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = BookAuthorModel::findOrNew($id);
        return $this->show(compact('model'));
    }

    public function saveAction() {
        $model = new BookAuthorModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('author')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        BookAuthorModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('author')
        ]);
    }
}