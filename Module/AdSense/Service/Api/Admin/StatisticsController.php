<?php
declare(strict_types=1);
namespace Module\AdSense\Service\Api\Admin;

use Module\AdSense\Domain\Repositories\StatisticsRepository;

final class StatisticsController extends Controller {

    public function indexAction() {
        return $this->render(StatisticsRepository::subtotal());
    }
}