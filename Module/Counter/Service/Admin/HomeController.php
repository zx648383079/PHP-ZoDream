<?php
declare(strict_types=1);
namespace Module\Counter\Service\Admin;

use Module\Counter\Domain\Repositories\StateRepository;
use Module\Counter\Domain\Repositories\StatisticsRepository;

class HomeController extends Controller {
    public function indexAction() {
        return $this->show();
    }

    public function todayAction() {
        $this->layout = '';
        return $this->show(StatisticsRepository::today());
    }


    public function trendAction() {
        $this->layout = '';
        return $this->show();
    }

    public function searchWordAction() {
        $this->layout = '';
        $items = StateRepository::searchKeywords(...StateRepository::getTimeInput());
        $items = array_splice($items, 0, 10);
        return $this->show(compact('items'));
    }

    public function sourceAction() {
        $this->layout = '';
        $items = StateRepository::allSource(...StateRepository::getTimeInput());
        $items = array_splice($items, 0, 10);
        return $this->show(compact('items'));
    }

    public function enterAction() {
        $this->layout = '';
        $items = StateRepository::enterUrl(...StateRepository::getTimeInput())->getItems();
        $items = array_splice($items, 0, 10);
        return $this->show(compact('items'));
    }

    public function urlAction() {
        $this->layout = '';
        $items = StateRepository::allUrl(...StateRepository::getTimeInput())->getItems();
        $items = array_splice($items, 0, 10);
        return $this->show(compact('items'));
    }

    public function districtAction() {
        $this->layout = '';
        return $this->show();
    }
}