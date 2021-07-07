<?php
namespace Module\Exam\Service;

use Domain\Model\SearchModel;
use Module\Exam\Domain\Model\CourseModel;

class HomeController extends Controller {

    public function indexAction(int $id = 0) {
        $course_list = CourseModel::with('children')
            ->where('parent_id', intval($id))->get();
        return $this->show(compact('course_list'));
    }

    public function suggestionAction(string $keywords = '') {
        $data = CourseModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'name');
         })->limit(4)->get();
        return $this->renderData($data);
    }
}