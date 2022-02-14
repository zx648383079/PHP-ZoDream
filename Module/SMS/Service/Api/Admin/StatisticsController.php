<?php
declare(strict_types=1);
namespace Module\SMS\Service\Api\Admin;

use Module\SMS\Domain\Repositories\StatisticsRepository;

final class StatisticsController extends Controller {

    public function indexAction() {
        return $this->render(StatisticsRepository::subtotal());
    }
}