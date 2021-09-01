<?php
declare(strict_types=1);
namespace Module\Blog\Service\Api\Admin;

use Module\Blog\Domain\Repositories\StatisticsRepository;

final class StatisticsController extends Controller {

    public function indexAction() {
        return $this->render(StatisticsRepository::subtotal());
    }
}