<?php
declare(strict_types=1);
namespace Module\Exam\Domain\Repositories;

use Module\Exam\Domain\Model\PageModel;
use Module\Exam\Domain\Model\QuestionModel;
use Module\Exam\Domain\Pager;

class PagerRepository {

    public static function create(int $course = 0, int $type = 0, int $id = 0) {
        if ($course > 0) {
            return Pager::create($course, $type);
        }
        if ($id < 1) {
            throw new \Exception('请选择试卷');
        }
        $model = PageModel::findOrThrow($id, '书卷不存在');
        if ($model->rule_type > 0) {
            return Pager::createId($model->rule_value)
                ->setTitle($model->name)
                ->setLimitTime($model->limit_time);
        }
        return Pager::createId(
                Pager::questionByRule($model->rule_value)
            )
            ->setTitle($model->name)
            ->setLimitTime($model->limit_time);
    }

    public static function check(array $data, int $id = 0) {
        $pager = new Pager();
        foreach ($data as $id => $item) {
            if (isset($item['id']) && $item['id'] > 0) {
                $id = $item['id'];
            }
            $pager->append($id)
                ->answer($item['answer'] ?? '',
                    $item['dynamic'] ?? null);
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
            if (isset($item['id']) && $item['id'] > 0) {
                $id = $item['id'];
            }
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