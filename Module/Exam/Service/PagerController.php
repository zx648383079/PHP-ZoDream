<?php
namespace Module\Exam\Service;

use Module\Exam\Domain\Model\CourseModel;
use Module\Exam\Domain\Pager;

class PagerController extends Controller {

    const CACHE_KEY = 'exam_pager';

    public function indexAction($course, $type = 0, $page = 1, $per_page = 1) {
        $param = compact('course', 'type', 'page', 'per_page');
        $course = CourseModel::find($course);
        $pager = $this->getPager($course->id, $type);
        $cart_list = $pager->getCard();
        $report = $pager->getReport();
        $items = $pager->getPage($page, $per_page);
        $previous_url = !empty($items['previous']) ? url('./pager', array_merge($param, ['page' => $items['previous']])) : null;
        $next_url = !empty($items['next']) ? url('./pager', array_merge($param, ['page' => $items['next']])) : null;
        $items = $items['items'];
        return $this->show(compact(
            'course', 'pager', 'type',
            'items', 'previous_url', 'report', 'cart_list',
            'next_url'));
    }

    public function checkAction() {
        $pager = $this->getPager();
        $pager->finish();
        session([self::CACHE_KEY => $pager]);
        return $this->jsonSuccess([
            'refresh' => true
        ], '交卷成功！');
    }

    public function saveAction($question) {
        $pager = $this->getPager();
        foreach ($question as $id => $item) {
            $pager->answer($id, $item['answer'], isset($item['dynamic']) ? $item['dynamic'] : null);
        }
        session([self::CACHE_KEY => $pager]);
        return $this->jsonSuccess(true);
    }

    /**
     * @param null $course
     * @param int $type
     * @return Pager
     * @throws \Exception
     */
    private function getPager($course = null, $type = 0) {
        if (session()->has(self::CACHE_KEY)) {
            return session(self::CACHE_KEY);
        }
        $pager = Pager::create($course, intval($type));
        session([self::CACHE_KEY => $pager]);
        return $pager;
    }

}