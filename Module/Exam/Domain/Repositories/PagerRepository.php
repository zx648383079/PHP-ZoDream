<?php
declare(strict_types=1);
namespace Module\Exam\Domain\Repositories;

use Module\Exam\Domain\Entities\PageEvaluateEntity;
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
        $pager = static::findLastPage($model);
        if (!empty($pager)) {
            return $pager;
        }
        return static::createNewPage($model);
    }

    protected static function findLastPage(PageModel $model): ?Pager {
        if (auth()->guest()) {
            return null;
        }
        /** @var PageEvaluateModel $log */
        $log = PageEvaluateModel::where('page_id', $model->id)
            ->where('user_id', auth()->id())
            ->where('status', PageEvaluateEntity::STATUS_NONE)
            ->where('created_at', '>', time() - $model->limit_time * 60)
            ->first();
        if (empty($log)) {
            return null;
        }
        $items = PageQuestionModel::with('question')->where('evaluate_id', $log->id)->orderBy('id', 'asc')->get();
        $pager = new Pager();
        $pager->id = $log->id;
        $pager->pageId = $model->id;
        $pager->startTime = $log->getAttributeSource('created_at');
        $pager->title = $model->name;
        $pager->limitTime = $model->limit_time;
        $pager->items = array_map(function (PageQuestionModel $item) {
            return [
                'id' => $item->question_id,
                'model' => $item->question,
                'answer' => $item->answer,
                'dynamic' => $item->content
            ];
        }, $items);
        return $pager;
    }

    protected static function createNewPage(PageModel $model): Pager {
        // 判断是否有答题记录 是否允许重复答题
        /** @var array $questionItems */
        $questionItems = $model->rule_value;
        if ($model->rule_type < 1) {
            return PageGenerator::createId(
                PageGenerator::questionByRule($questionItems)
            )
                ->setPageId($model->id)
                ->setTitle($model->name)
                ->setLimitTime($model->limit_time);
        }
        // 固定题目取最后一次的试卷
        $lastId = PageEvaluateModel::where('page_id', $model->id)
            ->max('id');
        if ($lastId < 1) {
            return PageGenerator::createId($questionItems)
                ->setPageId($model->id)
                ->setTitle($model->name)
                ->setLimitTime($model->limit_time);
        }
        $lastQuestionItems = PageQuestionModel::query()
            ->where('evaluate_id', $lastId)
            ->where('page_id', $model->id)
            ->selectRaw('question_id as id, content as dynamic, max_score as score')->asArray()
            ->get();
        if (!empty($lastQuestionItems)) {
            return PageGenerator::createId($lastQuestionItems, false)
                ->setPageId($model->id)
                ->setTitle($model->name)
                ->setLimitTime($model->limit_time);
        }
        return PageGenerator::createId($questionItems)
            ->setPageId($model->id)
            ->setTitle($model->name)
            ->setLimitTime($model->limit_time);
    }

    public static function createOrThrow(int $course = 0, int $type = 0, int $id = 0) {
        $pager = static::create($course, $type, $id);
        if ($pager->count() < 1) {
            throw new \Exception('无题目');
        }
        if ($pager->startTime < 1) {
            $pager->startTime = time();
        }
        if ($id > 0 && $pager->id < 1) {
            static::saveEvaluate($pager);
        }
        return $pager;
    }

    public static function check(array $data, int $id = 0, int $pageId = 0) {
        $pager = new Pager();
        $pager->id = $id;
        $pager->pageId = $pageId;
        foreach ($data as $qId => $item) {
            if (isset($item['id']) && $item['id'] > 0) {
                $qId = $item['id'];
            }
            $pager->append($qId)
                ->answer($qId, $item['answer'] ?? '', $item['dynamic'] ?? null);
        }
        $pager->finish();
        static::saveReport($pager);
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
        foreach ($data as $qId => $item) {
            if (isset($item['id']) && $item['id'] > 0) {
                $qId = $item['id'];
            }
            $items[] = PageGenerator::formatQuestion(QuestionModel::find($qId),
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
        if ($res['page_id'] < 1) {
            return;
        }
        $model = PageEvaluateModel::create([
            'page_id' => $res['page_id'],
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
        $pager->id = $model->id;
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

    private static function saveReport(Pager $pager) {
        $res = $pager->toArray();
        static::logWrong($res['data']);
        if ($pager->pageId < 1 && $pager->id < 1) {
            return;
        }
        $model = $pager->id > 0 ? PageEvaluateModel::where('user_id', auth()->id())
            ->where('id', $pager->id)
            ->where('status', PageEvaluateEntity::STATUS_NONE)->first() : PageEvaluateModel::where('user_id', auth()->id())
            ->where('page_id', $pager->pageId)
            ->where('status', PageEvaluateEntity::STATUS_NONE)
            ->orderBy('id', 'desc')->first();
        $isNew = empty($model);
        if ($isNew) {
            $model = PageEvaluateModel::create([
                'page_id' => $res['page_id'],
                'user_id' => auth()->id(),
                'spent_time' => $pager->limitTime,
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

    public static function save(array $data, int $id = 0, int $pageId = 0) {
        if ($id < 1) {
            return;
        }
        $log = PageEvaluateModel::findWithAuth($id);
        if (empty($log)) {
            throw new \Exception('保存失败');
        }
        foreach ($data as $qId => $item) {
            if (isset($item['id']) && $item['id'] > 0) {
                $qId = $item['id'];
            }
            PageQuestionModel::where('evaluate_id', $log->id)->where('question_id', $qId)
                ->update([
                    'answer' => $item['answer'] ?? '',
                ]);
        }
    }
}