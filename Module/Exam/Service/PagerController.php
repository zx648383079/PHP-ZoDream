<?php
namespace Module\Exam\Service;

use Module\Exam\Domain\Model\CourseModel;
use Module\Exam\Domain\PageGenerator;
use Module\Exam\Domain\Pager;

class PagerController extends Controller {

    const CACHE_KEY = 'exam_pager';

    public function indexAction(int $course, int $type = 0, int $page = 1, int $per_page = 1) {
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
        return $this->renderData([
            'refresh' => true
        ], '交卷成功！');
    }

    public function saveAction(array $question) {
        $pager = $this->getPager();
        foreach ($question as $id => $item) {
            $pager->answer($id, $item['answer'], $item['dynamic'] ?? null);
        }
        session([self::CACHE_KEY => $pager]);
        return $this->renderData(true);
    }

    /**
     * @param null $course
     * @param int $type
     * @return Pager
     * @throws \Exception
     */
    private function getPager(int $course = 0, int $type = 0) {
        if (session()->has(self::CACHE_KEY)) {
            return session(self::CACHE_KEY);
        }
        $pager = PageGenerator::create($course, $type);
        session([self::CACHE_KEY => $pager]);
        return $pager;
    }

}