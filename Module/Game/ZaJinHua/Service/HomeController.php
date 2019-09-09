<?php
namespace Module\Game\ZaJinHua\Service;

use Module\Game\Bank\Domain\Model\BankLogModel;
use Module\Game\Bank\Domain\Model\BankProductModel;
use Module\Game\ZaJinHua\Domain\Player;
use Module\Game\ZaJinHua\Domain\Room;

class HomeController extends Controller {

    public function indexAction() {
        $player = Player::load();
        return $this->show(compact('player'));
    }

    public function doAction($action, $money = 0) {
        $player = Player::load();
        $player->invoke($action, $money);
        if (app('request')->isAjax()) {
            $this->layout = false;
        }
        return $this->show('index', compact('player'));
    }
}