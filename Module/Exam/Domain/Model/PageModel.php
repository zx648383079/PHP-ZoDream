<?php
namespace Module\Exam\Domain\Model;


use Module\Auth\Domain\Model\UserSimpleModel;
use Module\Exam\Domain\Entities\PageEntity;
use Module\Exam\Domain\QuestionCompiler;
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
 * @property integer $user_id
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $score
 * @property integer $question_count
 */
class PageModel extends PageEntity {

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

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
        if (is_array($value) && $this->rule_type > 0) {
            $value = array_map(function ($item) {
                unset($item['title'], $item['type']);
                return $item;
            }, $value);
        }
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
}