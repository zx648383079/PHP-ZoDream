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
}