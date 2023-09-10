<?php
namespace Module\Cas\Domain\Model;

use Zodream\Http\Uri;

/**
 * Class ServiceModel
 * @package Module\Cas\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $host
 * @property integer $allow_proxy
 * @property integer $enabled
 * @property integer $created_at
 * @property integer $updated_at
 */
class ServiceModel extends BaseModel {

    public static function tableName(): string {
        return 'cas_service';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,60',
            'host' => 'required|string:0,200',
            'allow_proxy' => 'int:0,9',
            'enabled' => 'int:0,9',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'host' => 'Host',
            'allow_proxy' => 'Allow Proxy',
            'enabled' => 'Enabled',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @param Uri $url
     * @param bool $check
     * @return static
     */
    public static function findByUrl(Uri $url, $check = true) {
        $service = self::where('host', $url->getHost())->one();
        return empty($service) || ($check && !$service->enabled) ? false : $service;
    }
}