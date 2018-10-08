<?php
namespace Module\Bug\Service;


use Module\Bug\Domain\Model\BugModel;

class BugController extends Controller {

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
        $model_list = BugModel::page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = BugModel::findOrNew($id);
        return $this->show(compact('model'));
    }

    public function saveAction() {
        $model = new BugModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => url('./')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        BugModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => url('./')
        ]);
    }
}