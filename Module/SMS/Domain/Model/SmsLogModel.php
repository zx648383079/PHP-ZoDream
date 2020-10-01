<?php
namespace Module\SMS\Domain\Model;

use Domain\Model\Model;

/**
 * Class SmsLogModel
 * @package Module\SMS\Domain\Model
 * @property integer $id
 * @property integer $signature_id
 * @property integer $template_id
 * @property string $mobile
 * @property integer $type
 * @property string $content
 * @property integer $status
 * @property string $ip
 * @property integer $created_at
 * @property integer $updated_at
 */
class SmsLogModel extends Model {
    public static function tableName() {
        return 'sms_log';
    }

    protected function rules() {
        return [
            'signature_id' => 'int',
            'template_id' => 'int',
            'mobile' => 'string:0,20',
            'type' => 'int:0,127',
            'content' => 'string:0,255',
            'status' => 'int:0,127',
            'ip' => 'string:0,120',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'signature_id' => 'Signature Id',
            'template_id' => 'Template Id',
            'mobile' => 'Mobile',
            'type' => 'Type',
            'content' => 'Content',
            'status' => 'Status',
            'ip' => 'Ip',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}