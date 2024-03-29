<?php
namespace Module\Legwork\Domain\Model;

use Domain\Model\Model;

/**
 * Class CategoryProviderModel
 * @package Module\Legwork\Domain\Model
 * @property integer $user_id
 * @property integer $cat_id
 * @property integer $status
 */
class CategoryProviderModel extends Model {

    const STATUS_NONE = 0;
    const STATUS_ALLOW = 1;
    const STATUS_DISALLOW = 2;

    public static function tableName(): string {
        return 'leg_category_provider';
    }

    protected string $primaryKey = '';

    protected function rules(): array {
        return [
            'user_id' => 'required|int',
            'cat_id' => 'required|int',
            'status' => 'int:0,127',
        ];
    }

    protected function labels(): array {
        return [
            'user_id' => 'User Id',
            'cat_id' => 'Cat Id',
            'status' => 'Status',
        ];
    }
}