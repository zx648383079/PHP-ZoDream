<?php
namespace Module\Task\Service\Api;

use Module\Task\Domain\Repositories\ReviewRepository;
use Zodream\Helpers\Time;
use Zodream\Route\Controller\RestController;

class ReviewController extends RestController {

    public function rules() {
        return ['*' => '@'];
    }

    public function indexAction($type, $date = null) {
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