<?php
declare(strict_types=1);
namespace Module\Shop\Service\Mobile;


use Module\Shop\Domain\Models\Scene\Address;
use Module\Shop\Domain\Repositories\AddressRepository;


class AddressController extends Controller {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction(int $selected = 0) {
        $model_list = AddressRepository::getList();
        return $this->show(compact('model_list', 'selected'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction(int $id) {
        $model = $id > 0 ? AddressRepository::get($id) : new Address();
        if (!$model) {
            $this->redirect($this->getUrl('address'));
        }
        return $this->show('edit', compact('model'));
    }

    public function saveAction() {
        $data = request()->get();
        try {
            $address = AddressRepository::save($data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('address')
        ]);
    }

    public function deleteAction(int $id) {
        try {
            AddressRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('address')
        ]);
    }

    public function defaultAction(int $id) {
        try {
            AddressRepository::setDefault($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}