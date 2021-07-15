<?php
declare(strict_types=1);
namespace Module\Exam\Domain\Repositories;

use Module\Exam\Domain\Entities\QuestionWrongEntity;
use Module\Exam\Domain\Model\PageEvaluateModel;
use Module\Exam\Domain\Model\PageModel;
use Module\Exam\Domain\Model\PageQuestionModel;
use Module\Exam\Domain\Model\QuestionModel;
use Module\Exam\Domain\PageGenerator;
use Module\Exam\Domain\Pager;

class PagerRepository {

    public static function create(int $course = 0, int $type = 0, int $id = 0) {
        if ($course > 0) {
            return PageGenerator::create($course, $type);
        }
        if ($id < 1) {
            throw new \Exception('请选择试卷');
        }
        $model = PageModel::findOrThrow($id, '书卷不存在');
        // 判断是否有答题记录 是否允许重复答题
        if ($model->rule_type < 1) {
            return PageGenerator::createId(
                    PageGenerator::questionByRule($model->rule_value)
                )
                ->setId($id)
                ->setTitle($model->name)
                ->setLimitTime($model->limit_time);
        }
        $questionItems = $model->rule_value;
        // 固定题目取最后一次的试卷
        $lastId = PageEvaluateModel::where('page_id', $model->id)
            ->max('id');
        if ($lastId < 1) {
            return PageGenerator::createId($questionItems)
                ->setId($id)
                ->setTitle($model->name)
                ->setLimitTime($model->limit_time);
        }
        $questionItems = PageQuestionModel::query()
            ->where('evaluate_id', $lastId)
            ->where('page_id', $model->id)
            ->selectRaw('question_id as id, content as dynamic, max_score as score')->asArray()
            ->get();
        return PageGenerator::createId($questionItems, false)
            ->setId($id)
            ->setTitle($model->name)
            ->setLimitTime($model->limit_time);
    }

    public static function createOrThrow(int $course = 0, int $type = 0, int $id = 0) {
        $pager = static::create($course, $type, $id);
        if ($pager->count() < 1) {
            throw new \Exception('无题目');
        }
        if ($id > 0) {
            static::saveEvaluate($pager);
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
        return $model->format(null, null, false, false);
    }

    public static function questionCheck(array $data) {
        $items = [];
        foreach ($data as $id => $item) {
            if (isset($item['id']) && $item['id'] > 0) {
                $id = $item['id'];
            }
            $items[] = PageGenerator::formatQuestion(QuestionModel::find($id),
                $item['answer'] ?? '',
                $item['dynamic'] ?? null, false);
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

    private static function saveEvaluate(Pager $pager) {
        $res = $pager->toArray();
        if ($res['id'] < 1) {
            return;
        }
        $model = PageEvaluateModel::create([
            'page_id' => $res['id'],
            'user_id' => auth()->id(),
            'spent_time' => 0,
            'right' => 0,
            'wrong' => 0,
            'score' => 0,
            'status' => 0,
            'remark' => '',
        ]);
        if (empty($model)) {
            throw new \Exception('保存失败');
        }
        foreach ($res['data'] as $item) {
            PageQuestionModel::create([
                'page_id' => $model->page_id,
                'evaluate_id' => $model->id,
                'question_id' => $item['id'],
                'user_id' => $model->user_id,
                'content' => $item['dynamic'],
                'max_score' => intval($item['max_score']),
                'answer' => '',
                'status' => PageQuestionModel::STATUS_NONE,
            ]);
        }
    }

    private static function saveReport(Pager $pager, int $spentTime = 0) {
        $res = $pager->toArray();
        static::logWrong($res['data']);
        if ($res['id'] < 1) {
            return;
        }
        $model = PageEvaluateModel::where('user_id', auth()->id())
            ->where('page_id', $res['id'])
            ->where('status', PageEvaluateModel::STATUS_NONE)
            ->orderBy('id', 'desc')->first();
        $isNew = empty($model);
        if (empty($isNew)) {
            $model = PageEvaluateModel::create([
                'page_id' => $res['id'],
                'user_id' => auth()->id(),
                'spent_time' => $spentTime,
                'right' => $res['report']['right'],
                'wrong' => $res['report']['wrong'],
                'score' => $res['report']['score'],
                'status' => 1,
                'remark' => '',
            ]);
        } else {
            $model->spent_time = $model->getSpentTime();
            $model->right = $res['report']['right'];
            $model->wrong = $res['report']['wrong'];
            $model->score = $res['report']['scale'];
            $model->status = 1;
            $model->save();
        }
        if (empty($model)) {
            throw new \Exception('交卷失败');
        }
        foreach ($res['data'] as $item) {
            $status = $item['right'] > 0 ?
                PageQuestionModel::STATUS_SUCCESS :
                ($item['right'] < 0 ? PageQuestionModel::STATUS_FAILURE: 0);
            if ($isNew) {
                PageQuestionModel::create([
                    'page_id' => $model->page_id,
                    'evaluate_id' => $model->id,
                    'question_id' => $item['id'],
                    'user_id' => $model->user_id,
                    'content' => $item['dynamic'],
                    'answer' => $item['your_answer'],
                    'status' => $status,
                ]);
                continue;
            }
            PageQuestionModel::where([
                'page_id' => $model->page_id,
                'evaluate_id' => $model->id,
                'question_id' => $item['id'],
                'user_id' => $model->user_id,
            ])->update([
                'content' => $item['dynamic'],
                'answer' => $item['your_answer'],
                'status' => $status,
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