<?php
namespace Module\Game\TCG\Domain\Model;


use Domain\Model\Model;

/**
 * 玩家等级规则
 * @package Module\Game\TCG\Domain\Model
 */
class GradeRuleModel extends Model {
    public static function tableName() {
        return 'game_tcg_grade_rule';
    }
}