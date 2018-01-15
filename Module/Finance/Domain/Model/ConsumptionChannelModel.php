<?php
namespace Module\Finance\Domain\Model;

use Domain\Model\Model;

/**
 * 消费渠道
 * @package Module\Finance\Domain\Model
 */
class ConsumptionChannelModel extends Model {
    public static function tableName() {
        return 'consumption_channel';
    }


}