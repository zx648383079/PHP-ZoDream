<?php
declare(strict_types=1);
namespace Module\Task\Service;

use Module\Task\Domain\Repositories\ReviewRepository;
use Zodream\Helpers\Time;

class RecordController extends Controller {

    public function indexAction(string $type, string|null $date = null) {
        return $this->show(compact('type', 'date'));
    }

    public function chartAction(string $type, string|null $date = null, string|null $chart = null, bool $ignore = false) {
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