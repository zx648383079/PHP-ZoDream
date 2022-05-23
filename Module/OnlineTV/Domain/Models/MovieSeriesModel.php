<?php
declare(strict_types=1);
namespace Module\OnlineTV\Domain\Models;

use Domain\Model\Model;

/**
 *
 * @property integer $id
 * @property integer $movie_id
 * @property integer $episode
 * @property string $title
 * @property integer $updated_at
 * @property integer $created_at
 */
class MovieSeriesModel extends Model {

	public static function tableName() {
        return 'tv_movie_series';
    }

    protected function rules() {
        return [
            'movie_id' => 'required|int',
            'episode' => 'required|int',
            'title' => 'string:0,255',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'movie_id' => 'Movie Id',
            'episode' => 'Episode',
            'title' => 'Title',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

}