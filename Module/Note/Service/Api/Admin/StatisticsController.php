<?php
declare(strict_types=1);
namespace Module\Note\Service\Api\Admin;

use Module\Note\Domain\Repositories\StatisticsRepository;

final class StatisticsController extends Controller {

    public function indexAction() {
        return $this->render(StatisticsRepository::subtotal());
    }
}