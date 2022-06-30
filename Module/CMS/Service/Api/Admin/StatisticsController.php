<?php
declare(strict_types=1);
namespace Module\CMS\Service\Api\Admin;

use Module\CMS\Domain\Repositories\StatisticsRepository;

final class StatisticsController extends Controller {

    public function indexAction() {
        return $this->render(StatisticsRepository::subtotal());
    }
}