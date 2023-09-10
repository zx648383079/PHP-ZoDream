<?php
declare(strict_types=1);
namespace Module\OnlineTV\Domain\Models;

use Domain\Model\Model;

/**
 *
 * @property integer $id
 * @property string $title
 * @property string $film_title
 * @property string $translation_title
 * @property string $cover
 * @property string $director
 * @property string $screenwriter
 * @property string $leader
 * @property integer $cat_id
 * @property integer $area_id
 * @property string $age
 * @property string $language
 * @property string $release_date
 * @property integer $duration
 * @property string $description
 * @property string $content
 * @property integer $series_count
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class MovieModel extends Model {

	public static function tableName(): string {
        return 'tv_movie';
    }

    protected function rules(): array {
        return [
            'title' => 'required|string:0,255',
            'film_title' => 'string:0,255',
            'translation_title' => 'string:0,255',
            'cover' => 'string:0,255',
            'director' => 'string',
            'leader' => 'string',
            'screenwriter' => 'string',
            'cat_id' => 'int',
            'area_id' => 'int',
            'age' => 'string:0,4',
            'language' => 'string:0,10',
            'release_date' => 'string:0,255',
            'duration' => 'int',
            'description' => 'string:0,255',
            'content' => '',
            'series_count' => 'int',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'title' => 'Title',
            'film_title' => 'Film Title',
            'translation_title' => 'Translation Title',
            'cover' => 'Cover',
            'director' => 'Director',
            'leader' => 'Leader',
            'cat_id' => 'Cat Id',
            'area_id' => 'Area Id',
            'age' => 'Age',
            'language' => 'Language',
            'release_date' => 'Release Date',
            'duration' => 'Duration',
            'description' => 'Description',
            'content' => 'Content',
            'series_count' => 'Series Count',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    public function category() {
        return $this->hasOne(CategoryModel::class, 'id', 'cat_id');
    }

    public function area() {
        return $this->hasOne(AreaModel::class, 'id', 'area_id');
    }
}