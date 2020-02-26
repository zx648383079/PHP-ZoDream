<?php
namespace Module\Exam\Domain\Model;

use Module\Auth\Domain\Model\UserSimpleModel;
use Module\Exam\Domain\Entities\PageEvaluateEntity;
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
 * @property string $remark
 * @property integer $created_at
 * @property integer $updated_at
 */
class PageEvaluateModel extends PageEvaluateEntity {


    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function getSpentTime() {
        return empty($this->spent_time) ? ceil((time() - $this->getAttributeSource('created_at')) / 60) : $this->spent_time;
    }
}