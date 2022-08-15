<?php
declare(strict_types=1);
namespace Module\OnlineTV\Domain\Models;

use Domain\Model\Model;

/**
 *
 * @property integer $id
 * @property integer $movie_id
 * @property integer $series_id
 * @property integer $file_type
 * @property integer $definition
 * @property string $file
 * @property string $name
 * @property string $size
 */
class MovieFileModel extends Model {

    protected array $append = ['url'];

	public static function tableName() {
        return 'tv_movie_file';
    }

    protected function rules() {
        return [
            'movie_id' => 'required|int',
            'series_id' => 'int',
            'file_type' => 'int:0,127',
            'definition' => 'int:0,127',
            'file' => 'required|string:0,255',
            'name' => 'required|string:0,255',
            'size' => 'string:0,20',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'movie_id' => 'Movie Id',
            'series_id' => 'Series Id',
            'file_type' => 'File Type',
            'definition' => 'Definition',
            'file' => 'File',
            'name' => 'Name',
            'size' => 'Size',
        ];
    }

    public function getUrlAttribute() {
        return url('./movie/file', ['id' => $this->id]);
    }
}