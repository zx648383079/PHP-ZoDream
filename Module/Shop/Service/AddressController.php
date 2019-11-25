<?php
namespace Module\Shop\Service;


use Module\Shop\Domain\Models\AddressModel;
use Module\Shop\Domain\Models\Scene\Address;


class AddressController extends Controller {

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
        $model_list = Address::with('region')->page();
        return $this->sendWithShare()->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = AddressModel::with('region')->where('user_id', auth()->id())
            ->where('id', $id)->first();
        return $this->show(compact('model'));
    }

    public function infoAction($id) {
        $address = Address::with('region')->where('user_id', auth()->id())
            ->where('id', $id)->first();
        return $this->jsonSuccess($address);
    }

    public function saveAction() {
        $model = new AddressModel();
        $model->user_id = auth()->id();
        if ($model->load(null, ['user_id']) && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => url('./address')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        AddressModel::where('user_id', auth()->id())
            ->where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => url('./address')
        ]);
    }
}