<?php
namespace Module\Game\TCG\Domain\Model;


use Domain\Model\Model;

/**
 * 技能表
 * @package Module\Game\TCG\Domain\Model
 */
class SkillModel extends Model {
    public static function tableName() {
        return 'game_tcg_kill';
    }
}