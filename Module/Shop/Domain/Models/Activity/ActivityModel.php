<?php
namespace Module\Shop\Domain\Models\Activity;


use Domain\Model\Model;

/**
 * Class ActivityModel
 * @package Module\Shop\Domain\Models\Activity
 * @property string $name
 * @property string $thumb
 * @property integer $type
 * @property integer $scope_type
 * @property string $scope
 * @property string $configure
 * @property integer $start_at
 * @property integer $end_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class ActivityModel extends Model {

    const TYPE_AUCTION = 1; // 拍卖
    const TYPE_SEC_KILL = 2; // 秒杀
    const TYPE_GROUP_BUY = 3; // 团购
    const TYPE_PACKAGE = 4; // 优惠

    const SCOPE_ALL = 0;
    const SCOPE_GOODS = 1;
    const SCOPE_BRAND = 2;
    const SCOPE_CATEGORY = 3;

    public static function tableName() {
        return 'shop_activity';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,40',
            'thumb' => 'string:0,200',
            'type' => 'int:0,99',
            'scope_type' => 'int:0,9',
            'scope' => 'required',
            'configure' => 'required',
            'start_at' => 'int',
            'end_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '名称',
            'thumb' => 'Thumb',
            'type' => 'Type',
            'scope_type' => 'Scope Type',
            'scope' => 'Scope',
            'configure' => 'Configure',
            'start_at' => 'Start At',
            'end_at' => 'End At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


}