<?php
namespace Module\Exam\Domain\Entities;

use Domain\Entities\Entity;
/**
 * Class PageEvaluateModel
 * @property integer $id
 * @property integer $page_id
 * @property integer $user_id
 * @property integer $spent_time
 * @property integer $right
 * @property integer $wrong
 * @property integer $score
 * @property integer $status
 * @property integer $marker_id
 * @property string $remark
 * @property integer $created_at
 * @property integer $updated_at
 */
class PageEvaluateEntity extends Entity {

    const STATUS_NONE = 0;
    const STATUS_SUBMIT = 1; // 已交卷
    const STATUS_SCORING = 2; // 阅卷中
    const STATUS_FINISH = 3; // 已完成

    public static function tableName(): string {
        return 'exam_page_evaluate';
    }
    public function rules(): array {
        return [
            'page_id' => 'required|int',
            'user_id' => 'required|int',
            'spent_time' => 'int:0,99999',
            'right' => 'int:0,9999',
            'wrong' => 'int:0,9999',
            'score' => 'int:0,999',
            'status' => 'int:0,127',
            'marker_id' => 'int',
            'remark' => 'string:0,255',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'page_id' => 'Page Id',
            'user_id' => 'User Id',
            'spent_time' => 'Spent Time',
            'right' => 'Right',
            'wrong' => 'Wrong',
            'score' => 'Score',
            'status' => 'Status',
            'remark' => 'Remark',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}