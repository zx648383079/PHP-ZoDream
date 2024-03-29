<?php
declare(strict_types=1);
namespace Module\Task\Service\Api;

use Module\Task\Domain\Repositories\ReviewRepository;
use Zodream\Helpers\Time;

class ReviewController extends Controller {

    public function rules() {
        return ['*' => '@'];
    }

    public function indexAction(string $type, string $date = '', bool $ignore = false) {
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