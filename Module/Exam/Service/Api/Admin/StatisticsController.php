<?php
declare(strict_types=1);
namespace Module\Exam\Service\Api\Admin;

use Module\Exam\Domain\Repositories\StatisticsRepository;

final class StatisticsController extends Controller {

    public function indexAction() {
        return $this->render(StatisticsRepository::subtotal());
    }
}