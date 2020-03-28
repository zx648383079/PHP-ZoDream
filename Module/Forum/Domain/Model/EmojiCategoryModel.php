<?php
namespace Module\Forum\Domain\Model;

use Domain\Model\Model;

/**
 * Class EmojiCategoryModel
 * @package Module\Forum\Domain\Model
 * @property integer $id
 * @property string $name
 */
class EmojiCategoryModel extends Model {

    public $timestamps = false;

    public static function tableName() {
        return 'bbs_emoji_category';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,255',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
        ];
    }
}