<?php
namespace Module\Auth\Domain\Model\Rank;


use Domain\Model\Model;

class UserRankModel extends Model {

    public static function tableName() {
        return 'user_rank';
    }
}