<?php
namespace Module\Game\Miner\Service;

use Module\Game\Miner\Domain\Model\HouseModel;
use Module\Game\Miner\Domain\Model\PlayerModel;

class HouseController extends Controller {

    public function indexAction() {
        $house_list = HouseModel::query()->all();
        $player = PlayerModel::findCurrent();
        return $this->show(compact('house_list', 'player'));
    }

    public function buyAction($id) {
        if (PlayerModel::buyHouse($id)) {
            return $this->jsonSuccess([
                'url' => url('./')
            ]);
        }
        return $this->jsonFailure('您的账户余额不足');
    }
}