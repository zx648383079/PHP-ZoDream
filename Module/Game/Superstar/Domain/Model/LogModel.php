<?php
namespace Module\Game\Superstar\Domain\Model;


use Domain\Model\Model;

/**
 * 记录
 * @package Module\Game\Superstar\Domain\Model
 */
class LogModel extends Model {
    public static function tableName() {
        return 'game_tcg_log';
    }
}