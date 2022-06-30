<?php
declare(strict_types=1);
namespace Module\OnlineTV\Domain\Models;

use Domain\Model\Model;

/**
 *
 * @property integer $id
 * @property integer $music_id
 * @property integer $file_type
 * @property string $file
 * @property integer $created_at
 */
class MusicFileModel extends Model {

	public static function tableName() {
        return 'tv_music_file';
    }

    protected function rules() {
        return [
            'music_id' => 'required|int',
            'file_type' => 'int:0,127',
            'file' => 'required|string:0,255',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'music_id' => 'Music Id',
            'file_type' => 'File Type',
            'file' => 'File',
            'created_at' => 'Created At',
        ];
    }
}