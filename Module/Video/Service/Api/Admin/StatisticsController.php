<?php
declare(strict_types=1);
namespace Module\Video\Service\Api\Admin;

use Module\Video\Domain\Repositories\StatisticsRepository;

final class StatisticsController extends Controller {

    public function indexAction() {
        return $this->render(StatisticsRepository::subtotal());
    }
}