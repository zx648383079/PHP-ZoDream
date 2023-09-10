<?php
namespace Module\Exam\Domain\Entities;

use Domain\Entities\Entity;
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
 * @property integer $course_id
 * @property integer $course_grade
 * @property integer $question_count
 */
class PageEntity extends Entity {
    public static function tableName(): string {
        return 'exam_page';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,200',
            'rule_type' => 'int:0,127',
            'rule_value' => '',
            'start_at' => 'int',
            'end_at' => 'int',
            'limit_time' => 'int',
            'user_id' => 'required|int',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
            'score' => 'int',
            'question_count' => 'int',
            'course_id' => 'int',
            'course_grade' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => '试卷名',
            'rule_type' => '选题类型',
            'rule_value' => '题目组成',
            'start_at' => '开始时间',
            'end_at' => '结束时间',
            'limit_time' => '限时',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'user_id' => 'User Id',
            'status' => 'Status',
        ];
    }
}