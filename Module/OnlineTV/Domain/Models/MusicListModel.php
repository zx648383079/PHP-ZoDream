<?php
declare(strict_types=1);
namespace Module\OnlineTV\Domain\Models;

use Domain\Model\Model;

/**
 *
 * @property integer $id
 * @property string $title
 * @property integer $user_id
 * @property string $cover
 * @property string $description
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class MusicListModel extends Model {

	public static function tableName() {
        return 'tv_music_list';
    }

    protected function rules() {
        return [
            'title' => 'required|string:0,255',
            'user_id' => 'required|int',
            'cover' => 'string:0,255',
            'description' => 'string:0,255',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'title' => 'Title',
            'user_id' => 'User Id',
            'cover' => 'Cover',
            'description' => 'Description',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}