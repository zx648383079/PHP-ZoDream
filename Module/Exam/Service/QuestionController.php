<?php
namespace Module\Exam\Service;

use Module\Exam\Domain\Model\QuestionModel;

class QuestionController extends Controller {

    public function indexAction($id) {
        $model = QuestionModel::find($id);
        $course = $model->course;
        $question = $model->format(null, null, true);
        $previous_url = $next_url = null;
        $previous = QuestionModel::where('id', '<', $model->id)
            ->where('course_id', $model->course_id)
            ->orderBy('id desc')->first('id');
        if (!empty($previous)) {
            $previous_url = url('./question', ['id' => $previous->id]);
        }
        $next = QuestionModel::where('id', '>', $model->id)
            ->where('course_id', $model->course_id)
            ->orderBy('id asc')->first('id');
        if (!empty($next)) {
            $next_url = url('./question', ['id' => $next->id]);
        }
        return $this->show(compact('course', 'model', 'question', 'previous_url', 'next_url'));
    }

}