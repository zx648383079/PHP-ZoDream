<?php
declare(strict_types=1);
namespace Module\OnlineTV\Domain\Models;

use Domain\Model\Model;

/**
 *
 * @property integer $id
 * @property string $name
 * @property string $cover
 * @property string $album
 * @property string $artist
 * @property integer $duration
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class MusicModel extends Model {

	public static function tableName() {
        return 'tv_music';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,255',
            'cover' => 'string:0,255',
            'album' => 'string:0,20',
            'artist' => 'string:0,20',
            'duration' => 'int',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'cover' => 'Cover',
            'album' => 'Album',
            'artist' => 'Artist',
            'duration' => 'Duration',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    public function files() {
        return $this->hasMany(MusicFileModel::class, 'music_id', 'id');
    }
}