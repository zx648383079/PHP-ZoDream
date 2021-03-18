<?php
namespace Module\Video\Domain\Models;

use Domain\Model\Model;

/**
 * Class TagModel
 * @package Module\Video\Domain\Models
 * @property integer $id
 * @property string $name
 * @property integer $created_at
 */
class TagModel extends Model {
    public static function tableName() {
        return 'video_tag';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,20',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'created_at' => 'Created At',
        ];
    }
}