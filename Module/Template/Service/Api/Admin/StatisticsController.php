<?php
declare(strict_types=1);
namespace Module\Template\Service\Api\Admin;

use Module\Template\Domain\Repositories\StatisticsRepository;

final class StatisticsController extends Controller {

    public function indexAction() {
        return $this->render(StatisticsRepository::subtotal());
    }
}