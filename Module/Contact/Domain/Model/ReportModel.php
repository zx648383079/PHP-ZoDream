<?php
declare(strict_types=1);
namespace Module\Contact\Domain\Model;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;
use Zodream\Helpers\Json;

/**
 * @property integer $id
 * @property string $email
 * @property integer $item_type
 * @property integer $item_id
 * @property integer $type
 * @property string $title
 * @property string $content
 * @property string $files
 * @property integer $status
 * @property integer $user_id
 * @property string $ip
 * @property integer $updated_at
 * @property integer $created_at
 */
class ReportModel extends Model {

    public static function tableName() {
        return 'cif_report';
    }

    protected function rules() {
        return [
            'email' => 'string:0,50',
            'item_type' => 'int:0,127',
            'item_id' => 'int',
            'type' => 'int:0,127',
            'title' => 'string:0,255',
            'content' => 'string:0,255',
            'files' => 'string:0,255',
            'status' => 'int:0,127',
            'user_id' => 'int',
            'ip' => 'string:0,120',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'email' => 'Email',
            'item_type' => 'Item Type',
            'item_id' => 'Item Id',
            'type' => 'Type',
            'title' => 'Title',
            'content' => 'Content',
            'files' => 'Files',
            'status' => 'Status',
            'user_id' => 'User Id',
            'ip' => 'Ip',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function getFilesAttribute() {
        $value = $this->getAttributeSource('files');
        return empty($value) ? [] : Json::decode($value);
    }

    public function setFilesAttribute($value) {
        $this->setAttributeSource('files', is_array($value) ? Json::encode($value) : $value);
    }
}