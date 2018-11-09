<?php
namespace Module\Schedule\Service;

use Module\Schedule\Domain\Scheduler;
use Module\Schedule\Domain\ScheduleAble;
use Zodream\Service\Factory;

class HomeController extends Controller {
    public function indexAction() {
        $scheduler = new Scheduler(config('schedule', []));
        $this->registerSchedule($scheduler);
        $scheduler->run();
        return $this->showContent('complete!');
    }

    protected function registerSchedule(Scheduler $scheduler) {
        $data = Factory::config()->getConfigByFile('schedule');
        foreach ($data as $item) {
            if (is_callable($item)) {
                call_user_func($item, $scheduler);
                continue;
            }
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