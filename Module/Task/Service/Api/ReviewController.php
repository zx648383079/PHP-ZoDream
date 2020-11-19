<?php
namespace Module\Task\Service\Api;

use Module\Task\Domain\Repositories\ReviewRepository;
use Zodream\Helpers\Time;
use Zodream\Route\Controller\RestController;

class ReviewController extends RestController {

    public function rules() {
        return ['*' => '@'];
    }

    public function indexAction($type, $date = null, $ignore = false) {
        $time = empty($date) ? time() : strtotime($date);
        if ($type === 'week') {
            list($start_at, $end_at) = Time::week($time);
        } elseif ($type = 'month') {
            list($start_at, $end_at) = Time::month($time);
        } else {
            $start_at = $time;
            $end_at = $time + 86400;
        }
        $day_list = ReviewRepository::statistics($start_at, $end_at, $ignore);
        return $this->renderData($day_list);
    }
}