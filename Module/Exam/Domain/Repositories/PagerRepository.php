<?php
declare(strict_types=1);
namespace Module\Exam\Domain\Repositories;

use Module\Exam\Domain\Entities\QuestionWrongEntity;
use Module\Exam\Domain\Model\PageEvaluateModel;
use Module\Exam\Domain\Model\PageModel;
use Module\Exam\Domain\Model\PageQuestionModel;
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

    public static function createOrThrow(int $course = 0, int $type = 0, int $id = 0) {
        $pager = static::create($course, $type, $id);
        if ($pager->count() < 1) {
            throw new \Exception('无题目');
        }
        return $pager;
    }

    public static function check(array $data, int $pageId = 0, int $spentTime = 0) {
        $pager = new Pager();
        $pager->setId($pageId);
        foreach ($data as $id => $item) {
            if (isset($item['id']) && $item['id'] > 0) {
                $id = $item['id'];
            }
            $pager->append($id)
                ->answer($item['answer'] ?? '',
                    $item['dynamic'] ?? null);
        }
        $pager->finish();
        static::saveReport($pager, $spentTime);
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
        return $model->format(null, null, true);
    }

    public static function questionCheck(array $data) {
        $items = [];
        foreach ($data as $id => $item) {
            if (isset($item['id']) && $item['id'] > 0) {
                $id = $item['id'];
            }
            $items[] = Pager::formatQuestion(QuestionModel::find($id),
                $item['answer'] ?? '',
                $item['dynamic'] ?? null);
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

    private static function saveReport(Pager $pager, int $spentTime = 0) {
        $res = $pager->toArray();
        static::logWrong($res['data']);
        if ($res['id'] < 1) {
            return;
        }
        $model = PageEvaluateModel::create([
            'page_id' => $res['id'],
            'user_id' => auth()->id(),
            'spent_time' => $spentTime,
            'right' => $res['report']['right'],
            'wrong' => $res['report']['wrong'],
            'score' => $res['report']['scale'],
            'status' => 1,
            'remark' => '',
        ]);
        if (empty($model)) {
            throw new \Exception('交卷失败');
        }
        foreach ($res['data'] as $item) {
            PageQuestionModel::create([
                'page_id' => $model->page_id,
                'evaluate_id' => $model->id,
                'question_id' => $item['id'],
                'user_id' => $model->user_id,
                'content' => $item['dynamic'],
                'answer' => $item['your_answer'],
                'status' => $item['right'] > 0 ? PageQuestionModel::STATUS_SUCCESS : PageQuestionModel::STATUS_FAILURE,
            ]);
        }
    }

    public static function logWrong(array $items) {
        $userId = auth()->id();
        foreach ($items as $item) {
            if ($item['right'] < 1) {
                continue;
            }
            static::addLogWrong(intval($item['id']), $userId);
        }
    }

    public static function addLogWrong(int $id, int $userId, int $amount = 1) {
        $log = QuestionWrongEntity::where('question_id', $id)->where('user_id', $userId)
            ->first();
        if ($log) {
            $log->frequency += $amount;
            $log->save();
            return;
        }
        QuestionWrongEntity::create([
            'question_id' => $id,
            'user_id' => $userId,
            'frequency' => $amount,
        ]);
    }
}