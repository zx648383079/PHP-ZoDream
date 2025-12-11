<?php
declare(strict_types=1);
namespace Module\Task\Service;

use Module\Task\Domain\Repositories\ReviewRepository;
use Zodream\Helpers\Time;

class ReviewController extends Controller {

    public function indexAction(string $type, string|null $date = null) {
        return $this->show(compact('type', 'date'));
    }

    public function viewAction(string $type, string|null $date = null) {
        $time = strtotime(date('Y-m-d 00:00:00', empty($date) ? time() : strtotime($date)));
        if ($type === 'week') {
            list($start_at, $end_at) = Time::week($time, false);
        } elseif ($type === 'month') {
            list($start_at, $end_at) = Time::month($time, false);
        } else {
            $type = 'day';
            $start_at = $time;
            $end_at = $time + 86399;
        }
        $log_list = ReviewRepository::logList($start_at, $end_at, true);
        $this->layout = false;
        return $this->show($type, compact('log_list', 'start_at', 'end_at'));
    }
}