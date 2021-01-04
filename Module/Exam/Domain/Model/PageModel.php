<?php
namespace Module\Exam\Domain\Model;


use Module\Exam\Domain\Entities\PageEntity;
use Zodream\Database\Command;
use Zodream\Helpers\Json;

/**
 * Class PageModel
 * @property integer $id
 * @property string $name
 * @property integer $rule_type
 * @property string $rule_value
 * @property integer $start_at
 * @property integer $end_at
 * @property integer $limit_time
 * @property integer $created_at
 * @property integer $updated_at
 */
class PageModel extends PageEntity {

    public function getStartAtAttribute() {
        return $this->formatTimeAttribute('start_at');
    }

    public function setStartAtAttribute($value) {
        $this->__attributes['start_at'] = is_numeric($value) ? $value : strtotime($value);
    }

    public function getEndAtAttribute() {
        return $this->formatTimeAttribute('end_at');
    }

    public function setEndAtAttribute($value) {
        $this->__attributes['end_at'] = is_numeric($value) ? $value : strtotime($value);
    }

    public function getRuleValueAttribute() {
        $rules = $this->getAttributeSource('rule_value');
        if (empty($rules)) {
            return [];
        }
        return is_array($rules) ? $rules : Json::decode($rules);
    }

    public function setRuleValueAttribute($value) {
        $this->__attributes['rule_value'] = is_array($value) ? Json::encode($value) : $value;
    }

    public function setRule($rules) {
        if (empty($rules)) {
            return;
        }
        $items = [];
        foreach ($rules['question']['course'] as $i => $course) {
            if ($course < 1) {
                continue;
            }
            $types = [];
            foreach ($rules['question']['type'] as $type => $args) {
                if (isset($args[$i]) && $args[$i] > 0) {
                    $types[$type] = $args[$i];
                }
            }
            if (empty($types)) {
                continue;
            }
            $items[$course] = $types;
        }
        $this->rule_value = $items;
    }

    /**
     * @return QuestionModel[]
     */
    public function generateQuestion() {
        $data = [];
        foreach ($this->rule_value as $course_id => $types) {
            foreach ($types as $type => $count) {
                $question_list = QuestionModel::where('type', $type)
                    ->where('course_id', $course_id)
                    ->orderBy('rand()')->limit($count)->get();
                $data = array_merge($data, $question_list);
            }
        }
        return $data;
    }

    public function createQuestion($user_id) {
        /** @var PageEvaluateModel $model */
        $model = PageEvaluateModel::where('user_id', $user_id)
            ->where('page_id', $this->id)->orderBy('id', 'desc')
            ->first();
        if ($model && $model->status < 1 && ($this->limit_time === 0 ||
                $model->getSpentTime() < $this->limit_time * 60)) {
            return $model;
        }
        $model = PageEvaluateModel::create([
            'page_id' => $this->id,
            'user_id' => $user_id
        ]);
        if (!$model) {
            return false;
        }
        if ($this->rule_type == 1) {
            $other = PageEvaluateModel::where('page_id', $this->id)
                ->value('id');
            if ($other > 0) {
                $sql = sprintf('INSERT INTO %s (page_id, evaluate_id, question_id, content, user_id, created_at) 
                                        SELECT page_id, %s, question_id, content, %s, %s  FROM %s where evaluate_id = %s', PageQuestionModel::tableName(),
                    $model->id, $user_id, time(), PageQuestionModel::tableName(), $other);
                db()->execute($sql);
                return $model;
            }
        }
        $question_list = $this->generateQuestion();
        $items = [];
        foreach ($question_list as $item) {
            $dynamicItems = $item->generateDynamic();
            $items[] = [
                'page_id' => $this->id,
                'evaluate_id' => $model->id,
                'question_id' => $item->id,
                'user_id' => $user_id,
                'content' => empty($dynamicItems) ?
                    '' : base64_encode(Json::encode($dynamicItems)),
                'created_at' => time()
            ];
        }
        PageQuestionModel::query()->insert($items);
        return $model;
    }
}