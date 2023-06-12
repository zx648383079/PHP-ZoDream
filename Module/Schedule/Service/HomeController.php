<?php
namespace Module\Schedule\Service;

use Module\Schedule\Domain\Scheduler;
use Module\Schedule\Domain\ScheduleAble;
use Zodream\Infrastructure\Contracts\HttpContext as HttpContextInterface;
use Zodream\Infrastructure\Queue\QueueManager;
use Zodream\Infrastructure\Queue\Worker;
use Zodream\Infrastructure\Queue\WorkerOptions;
use Zodream\Route\ModuleRoute;

class HomeController extends Controller {
    public function indexAction(string $name = 'schedule') {
        QueueManager::logFailedJob();
        $scheduler = new Scheduler(config('schedule', []));
        $scheduler->call(function () {
            (new Worker())
                ->runNextJob('', '', new WorkerOptions());
        })->everyMinute();
        $this->registerSchedule($scheduler, $name);
        $scheduler->run();
        return $this->showContent('complete!');
    }

    protected function registerSchedule(Scheduler $scheduler, string $name = 'schedule') {
        $data = config($name);
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
        $modules = config('route.modules', []);
        foreach ($modules as $module) {
            $instance = ModuleRoute::moduleInstance($module, app(HttpContextInterface::class));
            if (!$instance instanceof ScheduleAble) {
                continue;
            }
            $instance->registerSchedule($scheduler);
        }
    }
}