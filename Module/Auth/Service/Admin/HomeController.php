<?php
declare(strict_types=1);
namespace Module\Auth\Service\Admin;


use Module\Auth\Domain\Repositories\StatisticsRepository;

class HomeController extends Controller {

    public function indexAction() {
        $data = StatisticsRepository::subtotal();
        return $this->show(compact('data'));
    }
}