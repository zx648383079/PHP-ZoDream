<?php
declare(strict_types=1);
namespace Module\Wallet\Domain;

use Module\Wallet\Domain\Entities\AccountLogEntity;
use Module\Wallet\Domain\Entities\WalletEntity;

/**
 * 资金账户资金流通
 */
final class FundAccount {
    const TYPE_SYSTEM = 1; // 系统自动
    const TYPE_RECHARGE = 6; // 用户充值
    const TYPE_ADMIN = 9; // 管理员充值
    const TYPE_AFFILIATE = 15; // 分销
    const TYPE_BUY_BLOG = 21;
    const TYPE_FORUM_BUY = 25;
    const TYPE_DEFAULT = 99;
    const TYPE_CHECK_IN = 30;
    const TYPE_BANK = 31;
    const TYPE_GAME = 40;
    const TYPE_SHOPPING = 60;

    const STATUS_WAITING_PAY = 0;
    const STATUS_PAID = 1;
    const STATUS_REFUND = 9;



    /**
     * 创建一条记录
     * @param int $user_id
     * @param int $type
     * @param int $item_id
     * @param float $money
     * @param float $total_money
     * @param string $remark
     * @param int $status
     * @return AccountLogEntity
     */
    public static function log(
        int $user_id, int $type, int $item_id, float $money, float $total_money, string $remark, int $status = 0) {
        return AccountLogEntity::create(
            compact('user_id', 'type', 'item_id', 'money', 'total_money', 'remark', 'status'));
    }

    /**
     * 更改用户金额，并记录
     * @param int $user_id
     * @param int $type
     * @param int $item_id
     * @param float $money
     * @param string $remark
     * @param int $status
     * @return bool|int
     * @throws \Exception
     */
    public static function change(
        int $user_id, int $type, int $item_id, float $money, string $remark, int $status = 1) {
        if (empty($user_id)) {
            $user_id = auth()->id();
        }
        $oldMoney = WalletEntity::query()->where('user_id', $user_id)
            ->value('money');
        $newMoney = floatval($oldMoney) + $money;
        if ($newMoney < 0) {
            return false;
        }
        WalletEntity::query()->where('user_id', $user_id)->update([
            'money' => $newMoney
        ]);
        $log = self::log($user_id, $type, $item_id, $money, $newMoney, $remark, $status);
        return $log->id;
    }

    /**
     * 通过callback 同步创建记录修改余额
     * @param int $user_id
     * @param int $type
     * @param callable $cb 最好返回 model
     * @param float $money
     * @param string $remark
     * @return bool|mixed
     * @throws \Exception
     */
    public static function changeAsync(
        int $user_id, int $type, callable $cb,
        float $money, string $remark) {
        if (empty($user_id)) {
            $user_id = auth()->id();
        }
        $oldMoney = WalletEntity::query()->where('user_id', $user_id)
            ->value('money');
        $newMoney = floatval($oldMoney) + $money;
        if ($newMoney < 0) {
            return false;
        }
        $log = self::log($user_id, $type, 0, $money, $newMoney, $remark, 0);
        if (empty($log)) {
            return false;
        }
        WalletEntity::query()->where('user_id', $user_id)->update([
            'money' => $newMoney
        ]);
        $model = call_user_func($cb, $log);
        if ($model === false) {
            self::refund($log);
            return false;
        }
        if (is_numeric($model)) {
            $log->item_id = $model;
        } elseif (isset($model['id'])) {
            $log->item_id = $model['id'];
        }
        self::paid($log);
        return $model;
    }

    /**
     * 付款给
     * @param int $user_id
     * @param int $type
     * @param callable $cb
     * @param float $money
     * @param string $remark
     * @param int $toUser
     * @return bool|mixed
     * @throws \Exception
     */
    public static function payTo(int $user_id, int $type, callable $cb, float $money, string $remark, int $toUser) {
        $money = abs($money);
        if ($user_id < 1) {
            $user_id = auth()->id();
        }
        if ($toUser < 1 || $user_id < 1 || $user_id == $toUser) {
            // 不能自己付款给自己
            return false;
        }
        $oldMoney = WalletEntity::query()->where('user_id', $user_id)
            ->value('money');
        $newMoney = floatval($oldMoney) - $money;
        if ($newMoney < 0) {
            return false;
        }
        $log = self::log($user_id, $type, 0, -$money, $newMoney, $remark, 0);
        if (empty($log)) {
            return false;
        }
        $oldMoneyTo = WalletEntity::query()->where('user_id', $toUser)
            ->value('money');
        $newMoneyTo = floatval($oldMoneyTo) + $money;
        $logTo = self::log($toUser, $type, 0, $money, $newMoneyTo, $remark, 0);
        WalletEntity::query()->where('user_id', $user_id)->update([
            'money' => $newMoney
        ]);
        WalletEntity::query()->where('user_id', $toUser)->update([
            'money' => $newMoneyTo
        ]);
        $model = call_user_func($cb, $log, $logTo);
        if ($model === false) {
            self::refund($log);
            self::refund($logTo);
            return false;
        }
        if (is_numeric($model)) {
            $logTo->item_id = $log->item_id = $model;
        } elseif (isset($model['id'])) {
            $logTo->item_id = $log->item_id = $model['id'];
        }
        self::paid($log);
        self::paid($logTo);
        return $model;
    }

    public static function isBought(int $id, int $type = self::TYPE_DEFAULT): bool {
        return AccountLogEntity::where('item_id', $id)
                ->where('type', $type)->count() > 0;
    }

    /**
     * 退款，并更改用户金额
     * @return bool|mixed
     * @throws \Exception
     */
    private static function refund(AccountLogEntity $log) {
        WalletEntity::query()->where('user_id', $log->user_id)
            ->updateDecrement('money', $log->money);
        $log->status = self::STATUS_REFUND;
        return $log->save();
    }

    /**
     * 设置支付，不操作用户余额
     * @return bool|mixed
     */
    private static function paid(AccountLogEntity $log) {
        $log->status = self::STATUS_PAID;
        return $log->save();
    }
}