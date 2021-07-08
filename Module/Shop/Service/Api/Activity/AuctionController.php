<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Activity;

use Module\Shop\Domain\Repositories\Activity\AuctionRepository;
use Module\Shop\Service\Api\Controller;

class AuctionController extends Controller {

    public function rules() {
        return [
            'bid' => '@',
            '*' => '*'
        ];
    }

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            AuctionRepository::getList($keywords)
        );
    }

    public function detailAction(int $id, bool $full = false) {
        try {
            return $this->render(AuctionRepository::auctionDetail($id, $full));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function logAction(int $activity) {
        return $this->renderPage(
            AuctionRepository::auctionLogList($activity)
        );
    }

    public function bidAction(int $activity, float $money = 0) {
        try {
            AuctionRepository::auctionBid($activity, $money);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}