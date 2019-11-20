<?php
namespace Module\Exam\Service;

use Module\Exam\Domain\Model\QuestionModel;
use Module\Exam\Domain\Pager;

class QuestionController extends Controller {

    const CACHE_KEY = 'exam_question';

    public function indexAction($id = 0, $course = 0) {
        if ($id > 0) {
            $model = QuestionModel::find($id);
        } elseif ($course > 0) {
            $model = QuestionModel::where('course_id', $course)
                ->orderBy('id asc')->first();
        }
        if (!isset($model) || empty($model)) {
            return $this->goBack();
        }
        $course = $model->course;
        list($cart_list, $previous_url, $next_url, $current) = $this->getCard($model->id, $model->course_id);
        $question = $model->format($current, null, true);
        return $this->show(compact('course',
            'cart_list',
            'model', 'question', 'previous_url', 'next_url'));
    }

    public function checkAction($question) {
        $this->layout = false;
        $items = [];
        $results = session(self::CACHE_KEY, []);
        foreach ($question as $id => $item) {
            $arg = Pager::formatQuestion(QuestionModel::find($id),
                isset($item['answer']) ? $item['answer'] : '',
                isset($item['dynamic']) ? $item['dynamic'] : null);
            $results[$arg['id']] = $arg['right'];
            $items[] = $arg;
        }
        session([
            self::CACHE_KEY => $results
        ]);
        return $this->show(compact('items'));
    }

    private function getCard($id, $course) {
        $data = QuestionModel::where('course_id', $course)
            ->orderBy('id asc')->pluck('id');
        $items = [];
        $previous_url = $next_url = null;
        $results = session(self::CACHE_KEY, []);
        $current = 0;
        foreach ($data as $i => $item) {
            $items[] = [
                'order' => $i + 1,
                'id' => $item,
                'active' => $item == $id,
                'right' => isset($results[$item]) ? $results[$item] : 0,
            ];
            if ($item != $id) {
                continue;
            }
            $current = $i + 1;
            if ($i > 0) {
                $previous_url = url('./question', ['id' => $data[$i - 1]]);
            }
            if ($i < count($data) - 1) {
                $next_url = url('./question', ['id' => $data[$i + 1]]);
            }
        }
        return [$items, $previous_url, $next_url, $current];
    }

}