<?php
namespace Module\SMS\Domain\Model;

use Domain\Model\Model;

/**
 * Class SmsSignatureModel
 * @package Module\SMS\Domain\Model
 * @property integer $id
 * @property string $sign_no
 * @property string $name
 * @property integer $is_default
 */
class SmsSignatureModel extends Model {
    public static function tableName() {
        return 'sms_signature';
    }

    protected function rules() {
        return [
            'sign_no' => 'string:0,32',
            'name' => 'required|string:0,20',
            'is_default' => 'int:0,127',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'sign_no' => 'Sign',
            'name' => 'Name',
            'is_default' => 'Is Default',
        ];
    }
}