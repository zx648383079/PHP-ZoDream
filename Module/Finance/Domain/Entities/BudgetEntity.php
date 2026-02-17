<?php
namespace Module\Finance\Domain\Entities;

use Domain\Entities\Entity;
/**
 * 预算
 * @property integer $id
 * @property string $name
 * @property string $remark
 * @property float $budget
 * @property float $spent
 * @property integer $cycle
 * @property integer $user_id
 * @property integer $deleted_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class BudgetEntity extends Entity {
    const CYCLE_ONCE = 0;
    const CYCLE_DAY = 1;
    const CYCLE_WEEK = 2;
    const CYCLE_MONTH = 3;
    const CYCLE_YEAR = 4;

    public static function tableName(): string {
        return 'finance_budget';
    }

    public function rules(): array {
        return [
            'name' => 'required|string:0,50',
            'remark' => 'string:0,255',
            'budget' => '',
            'spent' => '',
            'cycle' => 'int:0,9',
            'user_id' => 'required|int',
            'deleted_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => '名称',
            'budget' => '预算',
            'spent' => '已花费',
            'cycle' => '周期',
            'user_id' => 'User Id',
            'deleted_at' => '删除时间',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function scopeAuth($query) {
        return $query->where('user_id', auth()->id());
    }
}