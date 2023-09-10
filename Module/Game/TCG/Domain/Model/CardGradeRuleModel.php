<?php
namespace Module\Game\TCG\Domain\Model;


use Domain\Model\Model;

/**
 * 卡牌等级规则
 * @package Module\Game\TCG\Domain\Model
 */
class CardGradeRuleModel extends Model {
    public static function tableName(): string {
        return 'game_tcg_card_grade_rule';
    }
}