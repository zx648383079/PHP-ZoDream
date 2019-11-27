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
        if (app('request')->isAjax()) {
            $this->layout = false;
            return $this->show('page', compact('model_list'));
        }
        return $this->sendWithShare()->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        if (app('request')->isAjax()) {
            return $this->infoAction($id);
        }
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
        $data = app('request')->get();
        $data['user_id'] = auth()->id();
        if (isset($data['id']) && $data['id'] > 0
            && isset($data['tel']) && strpos($data['tel'], '****') > 0) {
            unset($data['tel']);
        }
        if (!$model->load($data) || !$model->autoIsNew()->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        return $this->jsonSuccess([
            'refresh' => true
        ]);
    }

    public function deleteAction($id) {
        AddressModel::where('user_id', auth()->id())
            ->where('id', $id)->delete();
        return $this->jsonSuccess([
            'refresh' => true
        ]);
    }

    public function defaultAction($id) {
        Address::defaultId($id);
        return $this->jsonSuccess([
            'refresh' => true
        ]);
    }
}