<?php
namespace Module\Game\TCG\Domain\Model;


use Domain\Model\Model;

/**
 * 卡牌
 * @package Module\Game\TCG\Domain\Model
 */
class CardModel extends Model {
    public static function tableName() {
        return 'game_tcg_card';
    }
}