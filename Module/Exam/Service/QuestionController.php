<?php
namespace Module\Exam\Service;

use Module\Exam\Domain\Model\CourseModel;
use Module\Exam\Domain\Model\QuestionModel;

class QuestionController extends Controller {

    public function indexAction($id) {
        $model = QuestionModel::find($id);
        $question = $model->format();
        $course = CourseModel::find($model->course_id);
        $question_list = QuestionModel::where('course_id', '>=', $model->course_id)->orderBy('id', 'asc')->pluck('id');
        return $this->show(compact('question', 'model', 'course', 'question_list'));
    }
}