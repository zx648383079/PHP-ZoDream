<?php
namespace Module\Forum\Domain\Model;

use Domain\Model\Model;

/**
 * Class EmojiModel
 * @package Module\Forum\Domain\Model
 * @property integer $id
 * @property integer $cat_id
 * @property string $name
 * @property string $icon
 */
class EmojiModel extends Model {

    public $timestamps = false;

    public static function tableName() {
        return 'bbs_emoji';
    }

    protected function rules() {
        return [
            'cat_id' => 'required|int',
            'name' => 'required|string:0,255',
            'icon' => 'required|string:0,255',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'cat_id' => 'Cat Id',
            'name' => 'Name',
            'icon' => 'Icon',
        ];
    }

}