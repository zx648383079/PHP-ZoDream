<?php
namespace Module\Game\TCG\Domain\Model;


use Domain\Model\Model;

/**
 * 奖池
 * @package Module\Game\TCG\Domain\Model
 */
class JackpotModel extends Model {
    public static function tableName() {
        return 'game_tcg_jackpot';
    }
}