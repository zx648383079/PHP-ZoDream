<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Models\CollectModel;

class StoreController extends Controller {

    public function indexAction(int $id) {
        return $this->render([
            'id' => $id,
            'name' => '我的店铺',
            'logo' => url()->asset('assets/images/zd.jpg'),
            'qr' => url()->asset('assets/images/wx.jpg'),
            'collect_count' => 1,
            'is_collected' => false
        ]);
    }

    public function toggleCollectAction(int $id) {
        if (CollectModel::exist($id)) {
            CollectModel::remove($id);
            return $this->render(['data' => false]);
        }
        CollectModel::add($id);
        return $this->render(['data' => true]);
    }
}