<?php
namespace Module\Game\ZaJinHua\Service;

use Module\Game\Bank\Domain\Model\BankLogModel;
use Module\Game\Bank\Domain\Model\BankProductModel;
use Module\Game\ZaJinHua\Domain\Player;
use Module\Game\ZaJinHua\Domain\Poker;
use Module\Game\ZaJinHua\Domain\Room;
use Exception;

class HomeController extends Controller {

    public function indexAction() {
        $player = Player::load();
        if ($player->status === Player::STATUS_FAILURE
            || $player->status === Player::STATUS_WINNER) {
            $player->clear();
        }
        if (app('request')->isPjax()) {
            $this->layout = false;
        }
        return $this->show(compact('player'));
    }

    public function doAction($action, $money = 0) {
        $player = Player::load();
        $message = null;
        try {
            $player->invoke($action, $money);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
        }
        if (app('request')->isPjax()) {
            $this->layout = false;
        }
        return $this->show('index', compact('player', 'message'));
    }
}