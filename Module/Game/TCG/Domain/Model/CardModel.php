<?php
namespace Module\Game\TCG\Domain\Model;


use Domain\Model\Model;

/**
 * 卡牌
 * @package Module\Game\TCG\Domain\Model
 */
class CardModel extends Model {
    public static function tableName(): string {
        return 'game_tcg_card';
    }
}