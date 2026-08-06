<?php
namespace Module\Finance\Domain\Entities;

use Domain\Entities\Entity;
/**
 * 资金账户
 * @package Module\Finance\Domain\Model
 * @property integer $id
 * @property string $name
 * @property float $price
 * @property integer $type
 * @property integer $status
 * @property string $remark
 * @property integer $user_id
 * @property integer $deleted_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class ItemEntity extends Entity {
    public static function tableName(): string {
        return 'finance_item';
    }

    public function rules(): array {
        return [
            'name' => 'required|string:0,35',
            'price' => 'numeric',
            'remark' => '',
            'type' => 'int:0,127',
            'status' => 'bool',
            'user_id' => 'required|int',
            'deleted_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => '物品名称',
            'price' => '购买金额',
            'type' => '类型',
            'status' => '状态',
            'remark' => '备注',
            'user_id' => 'User Id',
            'deleted_at' => '报废时间',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function scopeAuth($query) {
        return $query->where('user_id', auth()->id());
    }

    public function setDeletedAtAttribute($value) {
        if (!is_numeric($value)) {
            $value = strtotime($value);
        }
        $this->setAttributeToSource('deleted_at', $value < 0 ? 0 : $value);
    }

    public function getDeletedAtAttribute() {
        return $this->formatTimeAttribute('deleted_at');
    }
}