<?php
namespace Module\Book\Service\Admin;


use Module\Book\Domain\Model\BookAuthorModel;

class AuthorController extends Controller {
    public function indexAction($keywords = null) {
        $model_list = BookAuthorModel::when(!empty($keywords), function ($query) {
            BookAuthorModel::searchWhere($query, 'name');
        })->page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction($id) {
        $model = BookAuthorModel::findOrNew($id);
        return $this->show('edit', compact('model'));
    }

    public function saveAction() {
        $model = new BookAuthorModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->renderData([
                'url' => $this->getUrl('author')
            ]);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        BookAuthorModel::where('id', $id)->delete();
        return $this->renderData([
            'url' => $this->getUrl('author')
        ]);
    }
}