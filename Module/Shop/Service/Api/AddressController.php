<?php
namespace Module\Shop\Service\Api;


use Module\Shop\Domain\Models\RegionModel;
use Module\Shop\Domain\Repositories\AddressRepository;

class AddressController extends Controller {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction($id = 0) {
        if ($id > 0) {
            return $this->infoAction($id);
        }
        $address_list = AddressRepository::getList();
        return $this->renderPage($address_list);
    }

    public function infoAction($id) {
        $address = AddressRepository::get($id);
        if (!$address) {
            $this->renderFailure('地址有误');
        }
        return $this->render($address);
    }

    public function createAction() {
        $data = request()->validate([
            'id' => 'int',
            'name' => '',
            'region_id' => 'int',
            'region_name' => '',
            'tel' => '',
            'address' => '',
            'is_default' => ''
        ]);
        try {
            $address = AddressRepository::save($data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($address);
    }

    public function updateAction() {
        $data = request()->validate([
            'id' => 'int',
            'name' => '',
            'region_id' => 'int',
            'region_name' => '',
            'tel' => '',
            'address' => '',
            'is_default' => ''
        ]);
        try {
            $address = AddressRepository::save($data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($address);
    }

    public function deleteAction($id) {
        try {
            AddressRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render(['data' => true]);
    }

    public function defaultAction($id) {
        try {
            AddressRepository::setDefault($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render(['data' => true]);
    }
}