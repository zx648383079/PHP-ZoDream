<?php
declare(strict_types=1);

namespace Module\Exam\Domain;

use Module\Exam\Domain\Model\QuestionModel;

class PageGenerator {
    public static function create(int $course, int $type = 0) {
        if ($type < 2) {
            return static::createId(QuestionModel::where('course_id', $course)
                ->orderBy('id', 'asc')->get('id'), $type > 0)
                ->setTitle($type < 1 ? '顺序练习' : '随机练习');
        }
        if ($type === 3) {
            return static::createId(QuestionModel::where('course_id', $course)
                ->where('easiness', '>', 5)
                ->orderBy('id', 'asc')->get('id'), true)
                ->setTitle('难题练习');
        }
        $rules = [];
        foreach ([10, 5, 5, 3, 2] as $i => $amount) {
            $rules[] = [
                'course' => $course,
                'type' => $i,
                'amount' => $amount
            ];
        }
        return static::createId(static::questionByRule($rules), false)
            ->setTitle('全真模拟');
    }

    public static function questionByRule(array $data) {
        $items = [];
        $exist = [];
        foreach ($data as $item) {
            if ($item['amount'] < 1) {
                continue;
            }
            $args = QuestionModel::query()
                ->where('course_id', $item['course'])
                ->where('type', $item['type'])
                ->whereNotIn('id', $exist)
                ->orderBy('RAND()')
                ->limit($item['amount'])
                ->pluck('id');
            foreach ($args as $id) {
                $exist[] = $id;
                $items[] = [
                    'id' => $id,
                    'score' => $item['score']
                ];
            }
        }
        return $items;
    }

    public static function createId(array $items, $shuffle = false) {
        if ($shuffle) {
            shuffle($items);
        }
        return (new Pager())->setItems($items);
    }

    public static function formatQuestion(QuestionModel $model,
                                          $answer,
                                          $dynamic = null, bool $shuffle = true) {
        $data = $model->format(null,
            $dynamic,
            true, $shuffle);
        $data['your_answer'] = $answer;
        $data['right'] = QuestionChecker::check($model, $answer, $dynamic) == 1 ? 1 : -1;
        return $data;
    }

}