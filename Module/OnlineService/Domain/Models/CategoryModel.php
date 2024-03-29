<?php
declare(strict_types=1);
namespace Module\OnlineService\Domain\Models;

use Domain\Model\Model;

/**
 * Class CategoryModel
 * @package Module\OnlineService\Domain\Models
 * @property integer $id
 * @property string $name
 */
class CategoryModel extends Model {
    public static function tableName(): string {
        return 'service_category';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,255',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => 'Name',
        ];
    }

    public function words() {
        return $this->hasMany(CategoryWordModel::class, 'cat_id', 'id');
    }
}