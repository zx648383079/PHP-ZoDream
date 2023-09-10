<?php
namespace Module\SMS\Domain\Model;

use Domain\Model\Model;

/**
 * Class SmsTemplateModel
 * @package Module\SMS\Domain\Model
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property string $sign_no
 * @property string $content
 * @property integer $signature_id
 */
class SmsTemplateModel extends Model {
    public static function tableName(): string {
        return 'sms_template';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,50',
            'type' => 'int:0,127',
            'sign_no' => 'string:0,32',
            'content' => 'required|string:0,255',
            'signature_id' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'type' => 'Type',
            'sign_no' => 'Sign',
            'content' => 'Content',
            'signature_id' => 'Signature Id',
        ];
    }
}