<?php
namespace Module\Legwork\Domain\Model;

use Domain\Model\Model;

/**
 * Class ServiceRegionModel
 * @package Module\Legwork\Domain\Model
 * @property integer $service_id
 * @property integer $region_id
 */
class ServiceRegionModel extends Model {

    public static function tableName() {
        return 'leg_service_region';
    }

    protected $primaryKey = '';

    protected function rules() {
        return [
            'service_id' => 'required|int',
            'region_id' => 'required|int',
        ];
    }

    protected function labels() {
        return [
            'service_id' => 'Service Id',
            'region_id' => 'Region Id',
        ];
    }

}