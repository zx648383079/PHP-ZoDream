<?php
declare(strict_types=1);
namespace Module\SEO\Domain\Model;

use Domain\Model\Model;

/**
 * Class EmojiCategoryModel
 * @package Module\Forum\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $icon
 */
class EmojiCategoryModel extends Model {

    public $timestamps = false;

    public static function tableName(): string {
        return 'seo_emoji_category';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,255',
            'icon' => 'string:0,255',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'icon' => 'Icon',
        ];
    }

    public function items() {
        return $this->hasMany(EmojiModel::class, 'cat_id', 'id');
    }
}