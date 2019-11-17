<?php
namespace Module\Exam\Service;

use Module\Exam\Domain\Model\QuestionModel;

class QuestionController extends Controller {

    public function indexAction($id) {
        $model = QuestionModel::find($id);
        $course = $model->course;
        $question = $model->format();
        return $this->show(compact('course', 'model', 'question'));
    }

}