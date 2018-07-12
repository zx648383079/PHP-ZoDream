<?php
namespace Module\Shop\Service\Mobile;


use Module\Shop\Domain\Model\AddressModel;
use Zodream\Domain\Access\Auth;

class AddressController extends Controller {

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
        $model_list = AddressModel::page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = AddressModel::findOrNew($id);
        return $this->show(compact('model', 'cat_list'));
    }

    public function saveAction() {
        $model = new AddressModel();
        $model->user_id = Auth::id();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('address')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        AddressModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('address')
        ]);
    }
}