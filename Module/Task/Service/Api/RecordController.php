<?php
namespace Module\Task\Service\Api;

use Module\Task\Domain\Model\TaskDayModel;
use Zodream\Helpers\Time;
use Zodream\Route\Controller\RestController;

class RecordController extends RestController {

    public function rules() {
        return ['*' => '@'];
    }

    public function indexAction($type, $date = null, $chart = null, $ignore = false) {
        $time = empty($date) ? time() : strtotime($date);
        $data = [];
        if ($type === 'week') {
            list($start_at, $end_at) = Time::week($time);
        } else {
            $type = 'month';
            list($start_at, $end_at) = Time::month($time);
        }
        $data = TaskDayModel::time($start_at, $end_at)
            ->where('user_id', auth()->id())
            ->orderBy('today', 'asc')->get();
        $days = Time::rangeDate($start_at, $end_at);
        $day_list = [];
        foreach ($days as $day) {
            $day_list[$day] = [
                'day' => $day,
                'week' => Time::weekFormat($day),
                'amount' => 0,
                'complete_amount' => 0,
                'success_amount' => 0,
                'pause_amount' => 0,
                'failure_amount' => 0,
            ];
        }
        foreach ($data as $item) {
            $day_list[$item->today]['amount'] += $item->amount + $item->success_amount;
            $day_list[$item->today]['success_amount'] += $item->success_amount;
            $day_list[$item->today]['pause_amount'] += $item->pause_amount;
            $day_list[$item->today]['failure_amount'] += $item->failure_amount;
            if ($item->amount < 1) {
                $day_list[$item->today]['complete_amount'] ++;
            }
        }
        $day_list = array_values($day_list);
        if ($ignore) {
            $day_list = array_filter($day_list, function ($item) {
               return $item['amount'] > 0;
            });
        }
        $chart = $chart === 'table' ? $chart : 'chart';
        return $this->render(compact('date', 'type', 'day_list'));
    }

}