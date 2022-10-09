<?php
declare(strict_types=1);
namespace Module\Legwork\Service\Api\Admin;

use Module\Legwork\Domain\Repositories\StatisticsRepository;

final class StatisticsController extends Controller {

    public function indexAction() {
        return $this->render(StatisticsRepository::subtotal());
    }
}