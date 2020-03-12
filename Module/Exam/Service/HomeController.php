<?php
namespace Module\Exam\Service;

use Module\Exam\Domain\Model\CourseModel;

class HomeController extends Controller {

    public function indexAction($id = 0) {
        $course_list = CourseModel::with('children')
            ->where('parent_id', intval($id))->get();
        return $this->show(compact('course_list'));
    }

    public function suggestionAction($keywords = null) {
        $data = CourseModel::when(!empty($keywords), function ($query) {
            CourseModel::searchWhere($query, 'name');
         })->limit(4)->get();
        return $this->jsonSuccess($data);
    }
}