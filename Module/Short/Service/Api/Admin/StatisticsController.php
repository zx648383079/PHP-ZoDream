<?php
declare(strict_types=1);
namespace Module\Short\Service\Api\Admin;

use Module\Short\Domain\Repositories\StatisticsRepository;

final class StatisticsController extends Controller {

    public function indexAction() {
        return $this->render(StatisticsRepository::subtotal());
    }
}