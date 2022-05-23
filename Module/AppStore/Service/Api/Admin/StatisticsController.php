<?php
declare(strict_types=1);
namespace Module\AppStore\Service\Api\Admin;


use Module\AppStore\Domain\Repositories\StatisticsRepository;

final class StatisticsController extends Controller {

    public function indexAction() {
        return $this->render(StatisticsRepository::subtotal());
    }
}