<?php
namespace Module\Game\TCG\Domain\Model;


use Domain\Model\Model;

/**
 * 玩家拥有卡牌
 * @package Module\Game\TCG\Domain\Model
 */
class PlayerCardModel extends Model {
    public static function tableName(): string {
        return 'game_tcg_player_card';
    }
}