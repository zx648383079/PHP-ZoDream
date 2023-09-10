<?php
namespace Module\Game\TCG\Domain\Model;


use Domain\Model\Model;

/**
 * 记录
 * @package Module\Game\TCG\Domain\Model
 */
class LogModel extends Model {
    public static function tableName(): string {
        return 'game_tcg_log';
    }
}