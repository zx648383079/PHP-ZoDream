<?php
namespace Module\SMS\Doamin\Model;

use Domain\Model\Model;

class SmsTemplateModel extends Model {
    public static function tableName() {
        return 'sms_template';
    }
}