<?php
declare(strict_types=1);
namespace Module\Book\Service\Api\Admin;


use Module\Book\Domain\Repositories\StatisticsRepository;

final class StatisticsController extends Controller {

    public function indexAction() {
        return $this->render(StatisticsRepository::subtotal());
    }
}