<?php
declare(strict_types=1);
namespace Module\Contact\Service\Api\Admin;

use Module\Contact\Domain\Repositories\StatisticsRepository;

final class StatisticsController extends Controller {

    public function indexAction() {
        return $this->render(StatisticsRepository::subtotal());
    }
}