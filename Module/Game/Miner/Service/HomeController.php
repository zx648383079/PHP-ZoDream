<?php
namespace Module\Game\Miner\Service;

use Module\Game\Miner\Domain\Model\PlayerMinerModel;
use Module\Game\Miner\Domain\Model\PlayerModel;

class HomeController extends Controller {

    public function indexAction() {
        $player = PlayerModel::findCurrent();
        if ($player->isNewRecord) {
            return $this->redirect('./house');
        }
        $miner_list = PlayerMinerModel::with('miner', 'area')
            ->where('player_id', $player->id)->page();
        return $this->show(compact('player', 'miner_list'));
    }
}