<?php
declare(strict_types=1);
namespace Module\SEO\Service\Api\Admin;

use Module\SEO\Domain\Repositories\StatisticsRepository;

final class StatisticsController extends Controller {

    public function indexAction() {
        return $this->render(StatisticsRepository::subtotal());
    }
}