<?php
declare(strict_types=1);
namespace Module\Counter\Service\Api;

use Module\Counter\Domain\Repositories\StatisticsRepository;

final class StatisticsController extends Controller {

    public function indexAction() {
        return $this->render(StatisticsRepository::subtotal());
    }
}