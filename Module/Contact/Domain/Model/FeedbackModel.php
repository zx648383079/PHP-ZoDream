<?php
namespace Module\Contact\Domain\Model;


use Domain\Model\Model;

/**
 * Class FeedbackModel
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $content
 * @property integer $status
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class FeedbackModel extends Model {
    public static function tableName() {
        return 'cif_feedback';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,20',
            'email' => 'string:0,50',
            'phone' => 'string:0,30',
            'content' => 'string:0,255',
            'status' => 'int:0,9',
            'user_id' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'content' => 'Content',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}