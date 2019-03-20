<?php
namespace Module\Shop\Service\Api;


use Module\Shop\Domain\Model\Scene\Address;

class AddressController extends Controller {

    public function indexAction($id = 0) {
        if ($id > 0) {
            return $this->infoAction($id);
        }
        $address_list = Address::with('region')->where('user_id', auth()->id())->page();
        return $this->renderPage($address_list);
    }

    public function infoAction($id) {
        $address = Address::with('region')->where('user_id', auth()->id())
            ->where('id', $id)->first();
        return $this->render($address);
    }

    public function createAction($is_default = false) {
        $data = app('request')->validate([
            'name' => '',
            'region_id' => 'int',
            'tel' => '',
            'address' => ''
        ]);
        $address = new Address($data);
        $address->user_id = auth()->id();
        if (!$address->save()) {
            return $this->renderFailure($address->getFirstError());
        }
        if ($is_default) {
            Address::defaultId($address->id);
        }
        return $this->render($address);
    }

    public function updateAction($is_default = false) {
        $data = app('request')->validate([
            'id' => 'int',
            'name' => '',
            'region_id' => 'int',
            'tel' => '',
            'address' => ''
        ]);
        $address = Address::findWithAuth($data['id']);
        if (empty($address)) {
            return $this->renderFailure('id error');
        }
        if (isset($data['tel']) && strpos($data['tel'], '****') > 0) {
            unset($data['tel']);
        }
        $address->set($data);
        if (!$address->save()) {
            return $this->renderFailure($address->getFirstError());
        }
        if ($is_default) {
            Address::defaultId($address->id);
        }
        return $this->render($address);
    }

    public function deleteAction($id) {
        Address::where('user_id', auth()->id())->where('id', $id)->delete();
        return $this->render(['data' => true]);
    }

    public function defaultAction($id) {
        Address::defaultId($id);
        return $this->render(['data' => true]);
    }
}