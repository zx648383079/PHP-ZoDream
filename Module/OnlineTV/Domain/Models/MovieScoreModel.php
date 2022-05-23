<?php
declare(strict_types=1);
namespace Module\OnlineTV\Domain\Models;

use Domain\Model\Model;

/**
 *
 * @property integer $id
 * @property integer $movie_id
 * @property integer $score_type
 * @property string $score
 * @property string $url
 * @property integer $updated_at
 * @property integer $created_at
 */
class MovieScoreModel extends Model {

	public static function tableName() {
        return 'tv_movie_score';
    }

    protected function rules() {
        return [
            'movie_id' => 'required|int',
            'score_type' => 'required|int:0,127',
            'score' => 'required|string:0,10',
            'url' => 'string:0,255',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'movie_id' => 'Movie Id',
            'score_type' => 'Score Type',
            'score' => 'Score',
            'url' => 'Url',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}