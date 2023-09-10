<?php
namespace Module\Disk\Domain\Model;

use Module\Auth\Domain\Model\UserModel;
use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;

/**
 *
 * @property integer $id
 * @property string $token
 * @property string $ip
 * @property string $port
 * @property integer $can_upload
 * @property string $upload_url
 * @property string $download_url
 * @property string $ping_url
 * @property integer $file_count
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class ServerModel extends Model {

    public static function tableName(): string {
        return 'disk_server';
    }

    protected function rules(): array {
        return [
            'token' => 'required|string:0,255',
            'ip' => 'required|string:0,120',
            'port' => 'required|string:0,6',
            'can_upload' => 'required|int:0,127',
            'upload_url' => 'required|string:0,255',
            'download_url' => 'required|string:0,255',
            'ping_url' => 'required|string:0,255',
            'file_count' => 'required|int',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'token' => 'Token',
            'ip' => 'Ip',
            'port' => 'Port',
            'can_upload' => 'Can Upload',
            'upload_url' => 'Upload Url',
            'download_url' => 'Download Url',
            'ping_url' => 'Ping Url',
            'file_count' => 'File Count',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}