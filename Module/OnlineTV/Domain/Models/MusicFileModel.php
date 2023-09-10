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

    protected array $append = ['url'];

	public static function tableName(): string {
        return 'tv_music_file';
    }

    protected function rules(): array {
        return [
            'music_id' => 'required|int',
            'file_type' => 'int:0,127',
            'file' => 'required|string:0,255',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'music_id' => 'Music Id',
            'file_type' => 'File Type',
            'file' => 'File',
            'created_at' => 'Created At',
        ];
    }

    public function getUrlAttribute() {
        return url('./music/file', ['id' => $this->id]);
    }
}