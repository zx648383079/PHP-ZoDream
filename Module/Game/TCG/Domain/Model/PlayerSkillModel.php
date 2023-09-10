<?php
namespace Module\Game\TCG\Domain\Model;


use Domain\Model\Model;

/**
 * 玩家拥有的技能
 * @package Module\Game\TCG\Domain\Model
 */
class PlayerSkillModel extends Model {
    public static function tableName(): string {
        return 'game_tcg_player_skill';
    }
}