<?php
namespace Module\Shop\Service\Mobile;


use Module\Shop\Domain\Models\AddressModel;


class AddressController extends Controller {

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction($selected = 0) {
        $model_list = AddressModel::with('region')->page();
        return $this->show(compact('model_list', 'selected'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = AddressModel::findOrNew($id);
        return $this->show(compact('model'));
    }

    public function saveAction() {
        $model = new AddressModel();
        $model->user_id = auth()->id();
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

    public function defaultAction($id) {
        AddressModel::defaultId($id);
        return $this->jsonSuccess(true);
    }
}