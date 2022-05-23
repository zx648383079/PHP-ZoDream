<?php
declare(strict_types=1);
namespace Module\OnlineTV\Service\Api\Admin;


use Module\OnlineTV\Domain\Repositories\StatisticsRepository;

final class StatisticsController extends Controller {

    public function indexAction() {
        return $this->render(StatisticsRepository::subtotal());
    }
}