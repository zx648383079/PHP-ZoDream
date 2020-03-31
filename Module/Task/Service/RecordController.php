<?php
namespace Module\Task\Service;

use Module\Task\Domain\Repositories\ReviewRepository;
use Zodream\Helpers\Time;

class RecordController extends Controller {

    public function indexAction($type, $date = null) {
        return $this->show(compact('type', 'date'));
    }

    public function chartAction($type, $date = null, $chart = null, $ignore = false) {
        $time = empty($date) ? time() : strtotime($date);
        if ($type === 'week') {
            list($start_at, $end_at) = Time::week($time);
        } else {
            $type = 'month';
            list($start_at, $end_at) = Time::month($time);
        }
        $day_list = ReviewRepository::statistics($start_at, $end_at, $ignore);
        $this->layout = false;
        $chart = $chart === 'table' ? $chart : 'chart';
        return $this->show($chart, compact('date', 'type', 'day_list'));
    }

}