<?php
namespace Module\Game\Miner\Service;

use Module\Game\Miner\Domain\Model\MinerModel;
use Module\Game\Miner\Domain\Model\PlayerModel;
use Exception;

class MinerController extends Controller {

    public function indexAction() {
        $miner_list = MinerModel::query()->all();
        return $this->show(compact('miner_list'));
    }

    public function hireAction($id) {
        try {
            PlayerModel::hireMiner($id);
            return $this->jsonSuccess([
                'url' => url('./')
            ]);
        } catch (Exception $ex) {
            return $this->jsonFailure($ex->getMessage());
        }
    }

    public function fireAction($id) {
        try {
            PlayerModel::fireMiner($id);
            return $this->jsonSuccess([
                'url' => url('./')
            ]);
        } catch (Exception $ex) {
            return $this->jsonFailure($ex->getMessage());
        }
    }

    public function workAction($id, $area_id) {
        try {
            PlayerModel::workMiner($id, $area_id);
            return $this->jsonSuccess([
                'url' => url('./')
            ]);
        } catch (Exception $ex) {
            return $this->jsonFailure($ex->getMessage());
        }
    }

    public function balanceAction($id) {
        try {
            PlayerModel::balanceMiner($id);
            return $this->jsonSuccess([
                'url' => url('./')
            ]);
        } catch (Exception $ex) {
            return $this->jsonFailure($ex->getMessage());
        }
    }
}