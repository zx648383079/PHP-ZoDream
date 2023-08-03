<?php
declare(strict_types=1);
namespace Module\Catering\Service\Api\Admin;


use Module\Catering\Domain\Repositories\StatisticsRepository;

final class StatisticsController extends Controller {

    public function indexAction() {
        return $this->render(StatisticsRepository::subtotal());
    }
}