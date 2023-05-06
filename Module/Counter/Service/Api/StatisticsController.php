<?php
declare(strict_types=1);
namespace Module\Counter\Service\Api;

use Module\Counter\Domain\Repositories\StatisticsRepository;

final class StatisticsController extends Controller {

    public function indexAction(string $type = 'today') {
        return $this->render(StatisticsRepository::subtotal($type));
    }

    public function trendAction(string $type = 'today', int $compare = 0) {
        return $this->render(StatisticsRepository::trendAnalysis($type, $compare));
    }
}