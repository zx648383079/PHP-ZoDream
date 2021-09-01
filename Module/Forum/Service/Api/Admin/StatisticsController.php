<?php
declare(strict_types=1);
namespace Module\Forum\Service\Api\Admin;

use Module\Forum\Domain\Repositories\StatisticsRepository;

final class StatisticsController extends Controller {

    public function indexAction() {
        return $this->render(StatisticsRepository::subtotal());
    }
}