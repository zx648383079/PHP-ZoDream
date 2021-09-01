<?php
declare(strict_types=1);
namespace Module\Navigation\Service\Api\Admin;

use Module\Navigation\Domain\Repositories\StatisticsRepository;

final class StatisticsController extends Controller {

    public function indexAction() {
        return $this->render(StatisticsRepository::subtotal());
    }
}