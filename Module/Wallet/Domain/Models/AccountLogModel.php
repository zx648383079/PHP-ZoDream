<?php
namespace Module\Wallet\Domain\Models;

use Module\Auth\Domain\Model\UserSimpleModel;
use Module\Wallet\Domain\Entities\AccountLogEntity;

/**
 * 
 * @property integer $id
 * @property integer $user_id
 * @property integer $type
 * @property integer $item_id
 * @property integer $money
 * @property integer $total_money
 * @property integer $status
 * @property string $remark
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $credits
 * @property integer $total_credits
 */
class AccountLogModel extends AccountLogEntity {

    protected array $append = ['type_label', 'status_label'];


    
    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }


    public function getTypeLabelAttribute() {
        $type = $this->getAttributeSource('type');
        return trans('account_type.'.$type);
    }

    public function getStatusLabelAttribute() {
        $type = $this->getAttributeSource('status');
        return trans('account_status.'.$type);
    }
}