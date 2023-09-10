<?php
namespace Module\Shop\Domain\Models\Activity;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;

/**
 * Class BargainLogModel
 * @package Module\Shop\Domain\Models\Activity
 * @property integer $id
 * @property integer $bargain_id
 * @property integer $user_id
 * @property float $amount
 * @property float $price
 * @property integer $created_at
 */
class BargainLogModel extends Model {

    public static function tableName(): string {
        return 'shop_bargain_log';
    }

    protected function rules(): array {
        return [
            'bargain_id' => 'required|int',
            'user_id' => 'required|int',
            'amount' => 'string',
            'price' => 'string',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'bargain_id' => 'Bargain Id',
            'user_id' => 'User Id',
            'amount' => 'Amount',
            'price' => 'Price',
            'created_at' => 'Created At',
        ];
    }

    public function user()
    {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }
}