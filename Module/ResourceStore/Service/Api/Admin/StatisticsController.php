<?php
declare(strict_types=1);
namespace Module\ResourceStore\Service\Api\Admin;


use Module\ResourceStore\Domain\Repositories\StatisticsRepository;

final class StatisticsController extends Controller {

    public function indexAction() {
        return $this->render(StatisticsRepository::subtotal());
    }
}