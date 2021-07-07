<?php
namespace Module\Shop\Domain\Models\Activity;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;

class BargainLogModel extends Model {

    public static function tableName()
    {
        return 'shop_bargain_log';
    }

    public function user()
    {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }
}