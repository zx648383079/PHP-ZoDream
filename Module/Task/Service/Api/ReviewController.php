<?php
namespace Module\Task\Service\Api;

use Module\Task\Domain\Model\TaskLogModel;
use Zodream\Helpers\Time;
use Zodream\Route\Controller\RestController;

class ReviewController extends RestController {

    public function indexAction($type, $date = null) {
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
        $log_list = TaskLogModel::with('task')
            ->where('created_at', '>=', $start_at)
            ->where('created_at', '<=', $end_at)
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'asc')
            ->get();
        return $this->render(compact('log_list', 'start_at', 'end_at'));
    }
}