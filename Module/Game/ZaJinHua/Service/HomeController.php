<?php
declare(strict_types=1);
namespace Module\Game\ZaJinHua\Service;

use Module\Game\ZaJinHua\Domain\Player;
use Exception;

class HomeController extends Controller {

    public function indexAction() {
        $player = Player::load();
        if ($player->status === Player::STATUS_FAILURE
            || $player->status === Player::STATUS_WINNER) {
            $player->clear();
        }
        if (request()->isPjax()) {
            $this->layout = '';
        }
        return $this->show(compact('player'));
    }

    public function doAction(string $action, int $money = 0) {
        $player = Player::load();
        $message = null;
        try {
            $player->invoke($action, $money);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
        }
        if (request()->isPjax()) {
            $this->layout = '';
        }
        return $this->show('index', compact('player', 'message'));
    }
}