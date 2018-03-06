<?php
namespace Module\SMS\Doamin\Model;

use Domain\Model\Model;

class SmsSignatureModel extends Model {
    public static function tableName() {
        return 'sms_signature';
    }
}