<?php
namespace Module\Exam\Service;

use Module\Exam\Domain\Model\CourseModel;

class CourseController extends Controller {

    public function indexAction($id) {
        $course = CourseModel::find($id);
        $course_list = CourseModel::with('children')
            ->where('parent_id', intval($id))->get();
        return $this->show(compact('course_list', 'course'));
    }
}