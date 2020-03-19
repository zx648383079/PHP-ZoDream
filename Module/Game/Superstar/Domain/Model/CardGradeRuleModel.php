<?php
namespace Module\Game\Superstar\Domain\Model;


use Domain\Model\Model;

/**
 * 卡牌等级规则
 * @package Module\Game\Superstar\Domain\Model
 */
class CardGradeRuleModel extends Model {
    public static function tableName() {
        return 'game_tcg_card_grade_rule';
    }
}