<?php
namespace Module\Shop\Domain\Models\Activity;


use Domain\Model\Model;
use Module\Shop\Domain\Models\GoodsSimpleModel;
use Zodream\Database\Relation;
use Zodream\Helpers\Json;

/**
 * Class ActivityModel
 * @package Module\Shop\Domain\Models\Activity
 * @property string $name
 * @property string $thumb
 * @property string $description
 * @property integer $type
 * @property integer $scope_type
 * @property string $scope
 * @property string $configure
 * @property integer $status
 * @property integer $start_at
 * @property integer $end_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class ActivityModel extends Model {

    const TYPE_AUCTION = 1; // 拍卖
    const TYPE_SEC_KILL = 2; // 秒杀
    const TYPE_GROUP_BUY = 3; // 团购
    const TYPE_DISCOUNT = 4; // 优惠
    const TYPE_MIX = 5; // 组合
    const TYPE_CASH_BACK = 6; // 返现
    const TYPE_PRE_SALE = 7; // 预售
    const TYPE_BARGAIN = 8; // 砍价
    const TYPE_LOTTERY = 9; // 抽奖
    const TYPE_FREE_TRIAL = 10; // 试用

    const SCOPE_ALL = 0;
    const SCOPE_GOODS = 1;
    const SCOPE_BRAND = 2;
    const SCOPE_CATEGORY = 3;

    const STATUS_NONE = 0;
    const STATUS_END = 1;
    const STATUS_INVALID = 2;// 流拍

    public static function tableName() {
        return 'shop_activity';
    }

    public function rules() {
        return [
            'name' => 'required|string:0,40',
            'thumb' => 'string:0,200',
            'description' => 'string:0,200',
            'type' => 'int:0,99',
            'scope_type' => 'int:0,9',
            'scope' => 'string',
            'configure' => 'string',
            'status' => 'int',
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
            'description' => '说明',
            'thumb' => '图片',
            'type' => '活动类型',
            'scope_type' => '活动范围',
            'scope' => '范围区间',
            'configure' => '设置',
            'status' => '状态',
            'start_at' => '开始时间',
            'end_at' => '结束时间',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'configure[price]' => '价格',
        ];
    }

    public function goods() {
        return $this->hasOne(GoodsSimpleModel::class, 'id', 'scope');
    }

    public function setStartAtAttribute($value) {
        if (!is_numeric($value)) {
            $value = strtotime($value);
        }
        $this->__attributes['start_at'] = $value;
    }

    public function setEndAtAttribute($value) {
        if (!is_numeric($value)) {
            $value = strtotime($value);
        }
        $this->__attributes['end_at'] = $value;
    }

    public function setScopeAttribute($value) {
        if (is_array($value)) {
            $value = implode(',', $value);
        }
        $this->__attributes['scope'] = $value;
    }

    public function setConfigureAttribute($value) {
        if (is_array($value)) {
            $value = Json::encode($value);
        }
        $this->__attributes['configure'] = $value;
    }

    public function getConfigureAttribute() {
        return isset($this->__attributes['configure']) ? Json::decode($this->__attributes['configure']) : [];
    }

    public function getMixConfigureAttribute() {
        $configure = !isset($this->__attributes['configure'])
        || empty($this->__attributes['configure']) ? [
            'goods' => [],
            'price' => 0,
        ] : Json::decode($this->__attributes['configure']);
        $configure['goods'] = Relation::create($configure['goods'], [
            'goods' => [
                'type' => Relation::TYPE_ONE,
                'link' => ['goods_id' => 'id'],
                'query' => GoodsSimpleModel::query()
            ]
        ]);
        return $configure;
    }
}