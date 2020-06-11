<?php
namespace Module\Shop\Service\Api;

class StoreController extends Controller {

    public function indexAction($id) {
        return $this->render([
            'id' => $id,
            'name' => '我的店铺',
            'logo' => url()->asset('assets/images/zd.jpg'),
            'collect_count' => 1,
            'is_collected' => false
        ]);
    }
}