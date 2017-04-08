<?php
namespace Module\Auction\Service;

use Module\Auction\Domain\Model\AuctionLogModel;
use Module\ModuleController;

class HomeController extends ModuleController {

    public function indexAction() {

    }

    public function searchAction() {

    }

    public function auctionAction() {

    }

    public function logAction($id) {
        $data = AuctionLogModel::find()->alias('a')
            ->select('u.name, a.user_id, a.bid, a.status, a.create_at')
            ->left('user u', 'u.user_id = a.user_id')
            ->where(['a.auction_id' => $id])
            ->order('a.create_at desc')
            ->page();
        return $this->ajaxSuccess($data->toJson());
    }
}