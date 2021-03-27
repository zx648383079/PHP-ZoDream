<?php
declare(strict_types=1);
namespace Module\Exam\Domain\Repositories;

use Module\Exam\Domain\Model\QuestionModel;
use Module\Exam\Domain\Pager;

class PagerRepository {

    public static function create(int $course, int $type = 0) {
        return Pager::create($course, $type);
    }

    public static function check(array $data) {
        $pager = new Pager();
        foreach ($data as $id => $item) {
            $pager->append($id)
                ->answer(isset($item['answer']) ? $item['answer'] : '',
                    isset($item['dynamic']) ? $item['dynamic'] : null);
        }
        $pager->finish();
        return $pager;
    }

    public static function question(int $id = 0, int $course = 0) {
        if ($id > 0) {
            $model = QuestionModel::find($id);
        } elseif ($course > 0) {
            $model = QuestionModel::where('course_id', $course)
                ->orderBy('id asc')->first();
        }
        if (!isset($model) || empty($model)) {
            throw new \Exception('题目不存在');
        }
        return $model->format();
    }

    public static function questionCheck(array $data) {
        $items = [];
        foreach ($data as $id => $item) {
            $items[] = Pager::formatQuestion(QuestionModel::find($id),
                isset($item['answer']) ? $item['answer'] : '',
                isset($item['dynamic']) ? $item['dynamic'] : null);
        }
        return $items;
    }

    public static function questionCard(int $course) {
        $data = QuestionModel::where('course_id', $course)
            ->orderBy('id asc')->pluck('id');
        $items = [];
        foreach ($data as $i => $item) {
            $items[] = [
                'order' => $i + 1,
                'id' => $item,
            ];
        }
        return $items;
    }
}