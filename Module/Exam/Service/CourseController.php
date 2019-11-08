<?php
namespace Module\Exam\Service;

use Module\Exam\Domain\Model\CourseModel;

class CourseController extends Controller {

    public function indexAction($id = 0) {
        $course_list = CourseModel::with('children')
            ->where('parent_id', intval($id))->get();
        return $this->show(compact('course_list'));
    }
}