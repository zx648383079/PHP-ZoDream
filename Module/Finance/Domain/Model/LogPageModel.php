<?php
namespace Module\Finance\Domain\Model;

use Module\Finance\Domain\Entities\LogEntity;
use Zodream\Database\Model\Query;

/**
 * Class LogModel
 * @package Module\Finance\Domain\Model
 * @property integer $id
 * @property integer $parent_id
 * @property integer $type
 * @property float $money
 * @property float $frozen_money
 * @property integer $account_id
 * @property integer $channel_id
 * @property integer $project_id
 * @property integer $budget_id
 * @property string $remark
 * @property string $happened_at
 * @property string $out_trade_no
 * @property integer $user_id
 * @property string $trading_object
 * @property integer $created_at
 * @property integer $updated_at
 */
class LogPageModel extends LogModel {

}