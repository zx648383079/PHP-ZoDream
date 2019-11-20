<?php
namespace Module\Exam\Service\Api;

use Module\Exam\Domain\Model\QuestionModel;
use Module\Exam\Domain\Pager;
use Zodream\Route\Controller\RestController;

class QuestionController extends RestController {

    public function indexAction($id = 0, $course = 0) {
        if ($id > 0) {
            $model = QuestionModel::find($id);
        } elseif ($course > 0) {
            $model = QuestionModel::where('course_id', $course)
                ->orderBy('id asc')->first();
        }
        if (!isset($model) || empty($model)) {
            return $this->renderFailure('题目不存在');
        }
        $question = $model->format();
        return $this->render($question);
    }

    public function checkAction($question) {
        $data = [];
        foreach ($question as $id => $item) {
            $data[] = Pager::formatQuestion(QuestionModel::find($id),
                isset($item['answer']) ? $item['answer'] : '',
                isset($item['dynamic']) ? $item['dynamic'] : null);
        }
        return $this->render($data);
    }

    public function cardAction($course) {
        $data = QuestionModel::where('course_id', $course)
            ->orderBy('id asc')->pluck('id');
        $items = [];
        foreach ($data as $i => $item) {
            $items[] = [
                'order' => $i + 1,
                'id' => $item,
            ];
        }
        return $this->render($items);
    }

}