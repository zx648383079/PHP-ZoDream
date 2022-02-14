<?php
declare(strict_types=1);
namespace Module\OpenPlatform\Service\Api\Admin;

use Module\OpenPlatform\Domain\Repositories\StatisticsRepository;

final class StatisticsController extends Controller {

    public function indexAction() {
        return $this->render(StatisticsRepository::subtotal());
    }
}