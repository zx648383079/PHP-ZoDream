<?php
declare(strict_types=1);
namespace Module\Task\Service\Api;

use Module\Task\Domain\Repositories\ReviewRepository;
use Zodream\Helpers\Time;

class RecordController extends Controller {

    public function rules() {
        return ['*' => '@'];
    }

    public function indexAction(string $type, string $date = '') {
        $time = strtotime(date('Y-m-d 00:00:00', empty($date) ? time() : strtotime($date)));
        if ($type === 'week') {
            list($start_at, $end_at) = Time::week($time, false);
        } elseif ($type === 'month') {
            list($start_at, $end_at) = Time::month($time, false);
        } else {
            $start_at = $time;
            $end_at = $time + 86399;
        }
        $log_list = ReviewRepository::logList($start_at, $end_at);
        return $this->renderPage($log_list);
    }

}