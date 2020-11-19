<?php
namespace Module\Shop\Service;


use Module\Shop\Domain\Models\Scene\Address;
use Module\Shop\Domain\Repositories\AddressRepository;

class AddressController extends Controller {

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
        $model_list = AddressRepository::getList();
        if (app('request')->isAjax()) {
            $this->layout = false;
            return $this->show('page', compact('model_list'));
        }
        return $this->sendWithShare()->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => 0]);
    }

    public function editAction($id) {
        if (app('request')->isAjax()) {
            return $this->infoAction($id);
        }
        $model = $id > 0 ? AddressRepository::get($id) : new Address();
        if (!$model) {
            $this->redirect('./address');
        }
        return $this->show(compact('model'));
    }

    public function infoAction($id) {
        $address = AddressRepository::get($id);
        return $this->renderData($address);
    }

    public function saveAction() {
        $data = app('request')->get();
        try {
            $address = AddressRepository::save($data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function deleteAction($id) {
        try {
            AddressRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function defaultAction($id) {
        try {
            AddressRepository::setDefault($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }
}