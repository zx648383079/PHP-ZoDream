<?php
declare(strict_types=1);
namespace Module\MessageService\Service\Api\Admin;

use Module\MessageService\Domain\Repositories\StatisticsRepository;

final class StatisticsController extends Controller {

    public function indexAction() {
        return $this->render(StatisticsRepository::subtotal());
    }
}