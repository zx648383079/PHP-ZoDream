<?php
namespace Module\Game\TCG\Domain\Model;


use Domain\Model\Model;

/**
 * 玩家数据表
 * @package Module\Game\TCG\Domain\Model
 */
class PlayerModel extends Model {
    public static function tableName() {
        return 'game_tcg_player';
    }
}