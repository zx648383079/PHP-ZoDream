<?php
namespace Module\Auth\Domain\Model\Rank;


use Domain\Model\Model;

class RankModel extends Model {

    public static function tableName(): string {
        return 'rank';
    }
}