<?php
namespace Module\Schedule\Service;

use Module\Schedule\Domain\Scheduler;
use Module\Schedule\Domain\ScheduleAble;
use Zodream\Service\Factory;

class HomeController extends Controller {
    public function indexAction() {
        $scheduler = new Scheduler();
        $this->registerSchedule($scheduler);
        $scheduler->run();
    }

    protected function registerSchedule(Scheduler $scheduler) {
        $data = Factory::config()->getConfigByFile('schedule');
        foreach ($data as $item) {
            if (!class_exists($item)) {
                continue;
            }
            $instance = new $item;
            if (!$instance instanceof ScheduleAble) {
                continue;
            }
            $instance->registerSchedule($scheduler);
        }
    }
}