<?php
declare(strict_types=1);
namespace Module\Finance\Service\Api;

use Module\Finance\Domain\Repositories\StatisticsRepository;

final class StatisticsController extends Controller {

    public function indexAction(string $start_at = '', string $end_at = '', int $type = 0) {
        return $this->render(StatisticsRepository::subtotal($start_at, $end_at, $type));
    }

    public function budgetAction(string $start_at = '', string $end_at = '') {
        return $this->renderData(StatisticsRepository::bugetWithMonth($start_at, $end_at));
    }
}