<?php
namespace Module\Forum\Domain\Model;

use Domain\Model\Model;

/**
 * Class EmojiModel
 * @package Module\Forum\Domain\Model
 * @property integer $id
 * @property integer $cat_id
 * @property string $name
 * @property integer $type
 * @property string $content
 */
class EmojiModel extends Model {

    const TYPE_IMAGE = 0;
    const TYPE_TEXT = 1;

    public $timestamps = false;

    public static function tableName() {
        return 'bbs_emoji';
    }

    protected function rules() {
        return [
            'cat_id' => 'required|int',
            'name' => 'required|string:0,255',
            'type' => 'int:0,127',
            'content' => 'required|string:0,255',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'cat_id' => 'Cat Id',
            'name' => 'Name',
            'type' => 'Type',
            'content' => 'Content',
        ];
    }

    public function category() {
        return $this->hasOne(EmojiCategoryModel::class, 'id', 'cat_id');
    }

}