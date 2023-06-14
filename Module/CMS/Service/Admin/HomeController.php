<?php
declare(strict_types=1);
namespace Module\CMS\Service\Admin;

use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Domain\Repositories\StatisticsRepository;

class HomeController extends Controller {
    public function indexAction() {
        $data = StatisticsRepository::subtotal();
        return $this->show(compact('data'));
    }

    public function generateAction(string $name) {
        return $this->renderData(CMSRepository::generateTableName($name));
    }
}