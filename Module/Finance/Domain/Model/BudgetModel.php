<?php
namespace Module\Finance\Domain\Model;

use Domain\Model\Model;

/**
 * 预算
 * @package Module\Finance\Domain\Model
 * @property integer $id
 * @property string $name
 * @property float $budget
 * @property float $spent
 * @property integer $deleted_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class BudgetModel extends Model {
    public static function tableName() {
        return 'budget';
    }

    protected function rules() {
        return [
            'name' => 'required|string:3-50',
            'budget' => '',
            'spent' => '',
            'deleted_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'budget' => 'Budget',
            'spent' => 'Spent',
            'deleted_at' => 'Deleted At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getRemainAttribute() {
        return $this->budget - $this->spent;
    }
}