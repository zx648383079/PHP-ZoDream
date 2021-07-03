<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Activity;

use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Domain\Repositories\ActivityRepository;
use Module\Shop\Service\Api\Controller;

class AuctionController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            ActivityRepository::getList(ActivityModel::TYPE_AUCTION, $keywords)
        );
    }

    public function detailAction(int $id, bool $full = false) {
        try {
            return $this->render(ActivityRepository::auctionDetail($id, $full));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function logAction(int $activity) {
        return $this->renderPage(
            ActivityRepository::auctionLogList($activity)
        );
    }

    public function bidAction(int $activity, float $money = 0) {
        try {
            ActivityRepository::auctionBid($activity, $money);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}