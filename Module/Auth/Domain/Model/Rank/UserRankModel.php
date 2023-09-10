<?php
namespace Module\Auth\Domain\Model\Rank;


use Domain\Model\Model;

class UserRankModel extends Model {

    public static function tableName(): string {
        return 'user_rank';
    }
}