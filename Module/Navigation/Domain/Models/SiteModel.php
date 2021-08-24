<?php
declare(strict_types=1);
namespace Module\Navigation\Domain\Models;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;

/**
 * @property integer $id
 * @property string $schema
 * @property string $domain
 * @property string $name
 * @property string $logo
 * @property string $description
 * @property integer $cat_id
 * @property integer $user_id
 * @property integer $updated_at
 * @property integer $created_at
 */
class SiteModel extends Model {
    public static function tableName() {
        return 'search_site';
    }

    protected function rules() {
        return [
            'schema' => 'string:0,10',
            'domain' => 'required|string:0,100',
            'name' => 'required|string:0,30',
            'logo' => 'string:0,255',
            'description' => 'string:0,255',
            'cat_id' => 'int',
            'user_id' => 'int',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'schema' => 'Schema',
            'domain' => 'Domain',
            'name' => 'Name',
            'logo' => 'Logo',
            'description' => 'Description',
            'cat_id' => 'Cat Id',
            'user_id' => 'User Id',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    public function category() {
        return $this->hasOne(CategoryModel::class, 'id', 'cat_id');
    }
    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }
}
