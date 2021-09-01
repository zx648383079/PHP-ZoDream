<?php
declare(strict_types=1);
namespace Module\Blog\Service\Admin;

use Module\Blog\Domain\Repositories\StatisticsRepository;

class HomeController extends Controller {

    public function indexAction() {
        $subtotal = StatisticsRepository::subtotalMap(auth()->id());
        return $this->show(compact('subtotal'));
    }
}