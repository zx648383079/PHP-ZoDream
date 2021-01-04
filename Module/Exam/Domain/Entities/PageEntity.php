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
 * @property integer $created_at
 * @property integer $updated_at
 */
class PageEntity extends Entity {
    public static function tableName() {
        return 'exam_page';
    }

    public function rules() {
        return [
            'name' => 'required|string:0,200',
            'rule_type' => 'required|int:0,127',
            'rule_value' => 'required|string:0,255',
            'start_at' => 'int',
            'end_at' => 'int',
            'limit_time' => 'int:0,99999',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
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
        ];
    }
}