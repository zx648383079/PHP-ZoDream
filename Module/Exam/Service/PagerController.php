<?php
namespace Module\Exam\Service;

use Module\Exam\Domain\Model\CourseModel;
use Module\Exam\Domain\Pager;

class PagerController extends Controller {

    public function indexAction($course, $type = 0, $page = 1, $per_page = 1) {
        $param = compact('course', 'type', 'page', 'per_page');
        $course = CourseModel::find($course);
        $pager = Pager::create($course->id, intval($type));
        $cart_list = $pager->getCard();
        $report = $pager->getReport();
        $items = $pager->getPage($page, $per_page);
        $previous_url = !empty($items['previous']) ? url('./pager', array_merge($param, ['page' => $items['previous']])) : null;
        $next_url = !empty($items['next']) ? url('./pager', array_merge($param, ['page' => $items['next']])) : null;
        $items = $items['items'];
        return $this->show(compact('course', 'pager', 'type', 'items', 'previous_url', 'next_url'));
    }

}