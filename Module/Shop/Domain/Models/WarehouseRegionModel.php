<?php
namespace Module\Shop\Domain\Models;

use Domain\Model\Model;

/**
 * Class WarehouseRegionModel
 * @package Module\Shop\Domain\Models
 * @property integer $warehouse_id
 * @property integer $region_id
 */
class WarehouseRegionModel extends Model {

    protected string $primaryKey = '';

    public static function tableName(): string {
        return 'shop_warehouse_region';
    }

    protected function rules(): array {
        return [
            'warehouse_id' => 'required|int',
            'region_id' => 'required|int',
        ];
    }

    protected function labels(): array {
        return [
            'warehouse_id' => 'Warehouse Id',
            'region_id' => 'Region Id',
        ];
    }
}