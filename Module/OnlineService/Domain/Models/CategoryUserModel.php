<?php
declare(strict_types=1);
namespace Module\OnlineService\Domain\Models;


use Domain\Model\Model;

/**
 * Class CategoryUserModel
 * @package Module\OnlineService\Domain\Models
 * @property integer $cat_id
 * @property integer $user_id
 */
class CategoryUserModel extends Model {
    public static function tableName() {
        return 'service_category_user';
    }

    protected $primaryKey = '';

    protected function rules() {
        return [
            'cat_id' => 'required|int',
            'user_id' => 'required|int',
        ];
    }

    protected function labels() {
        return [
            'cat_id' => 'Cat Id',
            'user_id' => 'User Id',
        ];
    }
}