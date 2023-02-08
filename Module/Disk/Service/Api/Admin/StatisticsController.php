<?php
declare(strict_types=1);
namespace Module\Disk\Service\Api\Admin;

use Module\Disk\Domain\Repositories\StatisticsRepository;

final class StatisticsController extends Controller {

    public function indexAction() {
        return $this->render(StatisticsRepository::subtotal());
    }
}