<?php
declare(strict_types=1);
namespace Module\TradeTracker\Service\Api\Admin;

use Module\TradeTracker\Domain\Repositories\StatisticsRepository;

final class StatisticsController extends Controller {

    public function indexAction() {
        return $this->render(StatisticsRepository::subtotal());
    }
}