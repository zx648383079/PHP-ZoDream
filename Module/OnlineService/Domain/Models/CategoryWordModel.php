<?php
declare(strict_types=1);
namespace Module\OnlineService\Domain\Models;


use Domain\Model\Model;

/**
 * Class CategoryWordModel
 * @package Module\OnlineService\Domain\Models
 * @property integer $id
 * @property string $content
 * @property integer $cat_id
 */
class CategoryWordModel extends Model {
    public static function tableName(): string {
        return 'service_category_word';
    }

    protected function rules(): array {
        return [
            'content' => 'required|string:0,255',
            'cat_id' => 'required|int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'content' => 'Content',
            'cat_id' => 'Cat Id',
        ];
    }

    public function category() {
        return $this->hasOne(CategoryModel::class, 'id', 'cat_id');
    }

}