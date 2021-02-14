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

    public static function tableName() {
        return 'leg_category_provider';
    }

    protected $primaryKey = '';

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'cat_id' => 'required|int',
            'status' => 'int:0,127',
        ];
    }

    protected function labels() {
        return [
            'user_id' => 'User Id',
            'cat_id' => 'Cat Id',
            'status' => 'Status',
        ];
    }
}