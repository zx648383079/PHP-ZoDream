<?php
declare(strict_types=1);
namespace Module\OnlineService\Domain\Models;


use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;

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

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function category() {
        return $this->hasOne(CategoryModel::class, 'id', 'cat_id');
    }
}