<?php
namespace Module\Counter\Service\Admin;

use Module\Counter\Domain\Repositories\StateRepository;
use Module\Counter\Domain\Repositories\StatisticsRepository;

class HomeController extends Controller {
    public function indexAction() {
        return $this->show();
    }

    public function todayAction() {
        $this->layout = false;
        return $this->show(StatisticsRepository::today());
    }


    public function trendAction() {
        $this->layout = false;
        return $this->show();
    }

    public function searchWordAction() {
        $this->layout = false;
        $items = StateRepository::searchKeywords(...StateRepository::getTimeInput());
        $items = array_splice($items, 0, 10);
        return $this->show(compact('items'));
    }

    public function sourceAction() {
        $this->layout = false;
        $items = StateRepository::allSource(...StateRepository::getTimeInput());
        $items = array_splice($items, 0, 10);
        return $this->show(compact('items'));
    }

    public function enterAction() {
        $this->layout = false;
        $items = StateRepository::enterUrl(...StateRepository::getTimeInput())->getPage();
        $items = array_splice($items, 0, 10);
        return $this->show(compact('items'));
    }

    public function urlAction() {
        $this->layout = false;
        $items = StateRepository::allUrl(...StateRepository::getTimeInput())->getPage();
        $items = array_splice($items, 0, 10);
        return $this->show(compact('items'));
    }

    public function districtAction() {
        $this->layout = false;
        return $this->show();
    }
}