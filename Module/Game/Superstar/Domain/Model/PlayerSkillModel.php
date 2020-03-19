<?php
namespace Module\Game\Superstar\Domain\Model;


use Domain\Model\Model;

/**
 * 玩家拥有的技能
 * @package Module\Game\Superstar\Domain\Model
 */
class PlayerSkillModel extends Model {
    public static function tableName() {
        return 'game_tcg_player_skill';
    }
}