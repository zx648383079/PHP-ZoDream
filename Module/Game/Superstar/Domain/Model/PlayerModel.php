<?php
namespace Module\Game\Superstar\Domain\Model;


use Domain\Model\Model;

/**
 * 玩家数据表
 * @package Module\Game\Superstar\Domain\Model
 */
class PlayerModel extends Model {
    public static function tableName() {
        return 'game_tcg_player';
    }
}