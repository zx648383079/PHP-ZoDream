<?php
namespace Module\Exam\Service;

use Module\Exam\Domain\Model\CourseModel;
use Module\Exam\Domain\Model\QuestionModel;

class QuestionController extends Controller {

    public function indexAction($id) {
        $model = $this->sendQuestion($id);
        $course = $model->course;
        return $this->show(compact('course'));
    }

    private function sendQuestion($id) {
        $model = QuestionModel::find($id);
        if (empty($model)) {
            return false;
        }
        $question = $model->format();
        $question_list = QuestionModel::where('course_id', '>=', $model->course_id)->orderBy('id', 'asc')->pluck('id');
        $previous_url = $next_url = null;
        foreach($question_list as $i => $val) {
            if ($val != $id) {
                continue;
            }
            if ($i > 0) {
                $previous_url = url('./question', ['id' => $question_list[$i - 1]]);
            }
            if ($i < count($question_list) - 1) {
                $next_url = url('./question', ['id' => $question_list[$i + 1]]);
            }
        }
        $this->send(compact('question', 'previous_url', 'next_url', 'model', 'question_list'));
        return $model;
    }
}