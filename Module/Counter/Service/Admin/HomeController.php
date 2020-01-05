<?php
namespace Module\Counter\Service\Admin;

use Module\Counter\Domain\Repositories\StateRepository;

class HomeController extends Controller {
    public function indexAction() {
        return $this->show();
    }

    public function todayAction() {
        $this->layout = false;
        $time = strtotime(date('Y-m-d 00:00:00'));
        $today = StateRepository::statisticsByTime($time, $time + 86400);
        $yesterday = StateRepository::statisticsByTime($time - 86400, $time);
        $start = strtotime(date('Y-m-d H:00:00'));
        $yesterdayHour = StateRepository::statisticsByTime(
            $start, $start + 3600
        );
        $scale = (time() - $time) / 86400;
        $expectToday = [];
        foreach ($today as $k => $val) {
            $expectToday[$k] = intval($val / $scale);
        }
        return $this->show(compact('today', 'yesterday', 'expectToday', 'yesterdayHour'));
    }


    public function trendAction() {
        $this->layout = false;
        return $this->show();
    }

    public function searchWordAction() {
        $this->layout = false;
        $items = StateRepository::searchKeywords(...$this->getTimeInput());
        $items = array_splice($items, 0, 10);
        return $this->show(compact('items'));
    }

    public function sourceAction() {
        $this->layout = false;
        $items = StateRepository::allSource(...$this->getTimeInput());
        $items = array_splice($items, 0, 10);
        return $this->show(compact('items'));
    }

    public function enterAction() {
        $this->layout = false;
        $items = StateRepository::enterUrl(...$this->getTimeInput())->getPage();
        $items = array_splice($items, 0, 10);
        return $this->show(compact('items'));
    }

    public function urlAction() {
        $this->layout = false;
        $items = StateRepository::allUrl(...$this->getTimeInput())->getPage();
        $items = array_splice($items, 0, 10);
        return $this->show(compact('items'));
    }

    public function districtAction() {
        $this->layout = false;
        return $this->show();
    }
}