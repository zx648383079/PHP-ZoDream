<?php
namespace Module\Shop\Domain\Models\Activity;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;

/**
 * Class TrialReportModel
 * @package Module\Shop\Domain\Models\Activity
 * @property integer $id
 * @property integer $act_id
 * @property integer $user_id
 * @property integer $goods_id
 * @property string $title
 * @property string $content
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class TrialReportModel extends Model {

    public static function tableName(): string {
        return 'shop_trial_report';
    }

    protected function rules(): array {
        return [
            'act_id' => 'required|int',
            'user_id' => 'required|int',
            'goods_id' => 'required|int',
            'title' => 'required|string:0,255',
            'content' => '',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'act_id' => 'Act Id',
            'user_id' => 'User Id',
            'goods_id' => 'Goods Id',
            'title' => 'Title',
            'content' => 'Content',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    public function user()
    {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }
}