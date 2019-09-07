<?php
namespace Module\Game\TCG\Domain\Model;


use Domain\Model\Model;

/**
 * 商店
 * @package Module\Game\TCG\Domain\Model
 */
class StoreModel extends Model {
    public static function tableName() {
        return 'game_tcg_store';
    }
}