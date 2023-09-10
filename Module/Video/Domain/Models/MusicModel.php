<?php
namespace Module\Video\Domain\Models;

use Domain\Model\Model;

/**
 * 背景音乐
 * @package Module\Video\Domain\Models
 * @property integer $id
 * @property string $name
 * @property string $singer
 * @property integer $duration
 * @property string $path
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class MusicModel extends Model {
    public static function tableName(): string {
        return 'video_music';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,255',
            'singer' => 'string:0,20',
            'duration' => 'int:0,9999',
            'path' => 'required|string:0,255',
            'status' => 'int:0,127',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'singer' => 'Singer',
            'duration' => 'Duration',
            'path' => 'Path',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}