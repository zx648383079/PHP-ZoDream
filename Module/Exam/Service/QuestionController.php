<?php
namespace Module\Exam\Service;

use Module\Exam\Domain\Model\CourseModel;
use Module\Exam\Domain\Model\QuestionModel;
use Module\Exam\Domain\Pager;

class QuestionController extends Controller {

    public function indexAction($course, $type = 0) {
        $course = CourseModel::find($course);
        $pager = Pager::create($course->id, intval($type));
        return $this->show(compact('course', 'pager', 'type'));
    }

}