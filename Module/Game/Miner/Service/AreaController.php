<?php
namespace Module\Game\Miner\Service;

use Module\Game\Miner\Domain\Model\AreaModel;

class AreaController extends Controller {

    public function indexAction() {
        $area_list = AreaModel::query()->all();
        return $this->show(compact('area_list'));
    }
}