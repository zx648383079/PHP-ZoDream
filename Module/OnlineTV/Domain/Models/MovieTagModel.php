<?php
declare(strict_types=1);
namespace Module\OnlineTV\Domain\Models;

use Domain\Model\Model;

class MovieTagModel extends Model {

	public static function tableName() {
        return 'tv_movie_tag';
    }

}