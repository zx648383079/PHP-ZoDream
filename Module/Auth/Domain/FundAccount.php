<?php
declare(strict_types=1);
namespace Module\Auth\Domain;

use Module\Auth\Domain\Model\AccountLogModel;
use Module\Auth\Domain\Model\UserModel;

/**
 * 资金账户资金流通
 * @package Module\Auth\Domain
 */
final class FundAccount {
    const TYPE_SYSTEM = 1; // 系统自动
    const TYPE_ADMIN = 9; // 管理员充值
    const TYPE_AFFILIATE = 15; // 分销
    const TYPE_BUY_BLOG = 21;
    const TYPE_FORUM_BUY = 25;
    const TYPE_DEFAULT = 99;
    const TYPE_CHECK_IN = 30;
    const TYPE_BANK = 31;
    const TYPE_GAME = 40;
    const TYPE_SHOPPING = 60;

    /**
     * 创建一条记录
     * @param int $user_id
     * @param int $type
     * @param int $item_id
     * @param float $money
     * @param float $total_money
     * @param string $remark
     * @param int $status
     * @return AccountLogModel
     */
    public static function log(
        int $user_id, int $type, int $item_id, float $money, float $total_money, string $remark, int $status = 0) {
        return AccountLogModel::create(
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
        $oldMoney = UserModel::query()->where('id', $user_id)
            ->value('money');
        $newMoney = floatval($oldMoney) + $money;
        if ($newMoney < 0) {
            return false;
        }
        UserModel::query()->where('id', $user_id)->update([
            'money' => $newMoney
        ]);
        $log = self::log($user_id, $type, $item_id, $money, $newMoney, $remark, $status);
        if (auth()->id() === $user_id) {
            // 自动更新当前用户信息
            auth()->user()->money = $newMoney;
        }
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
        $oldMoney = UserModel::query()->where('id', $user_id)
            ->value('money');
        $newMoney = floatval($oldMoney) + $money;
        if ($newMoney < 0) {
            return false;
        }
        $log = self::log($user_id, $type, 0, $money, $newMoney, $remark, 0);
        if (empty($log)) {
            return false;
        }
        UserModel::query()->where('id', $user_id)->update([
            'money' => $newMoney
        ]);
        $model = call_user_func($cb, $log);
        if ($model === false) {
            $log->refund();
            return false;
        }
        if (is_numeric($model)) {
            $log->item_id = $model;
        } elseif (isset($model['id'])) {
            $log->item_id = $model['id'];
        }
        $log->paid();
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
        $oldMoney = UserModel::query()->where('id', $user_id)
            ->value('money');
        $newMoney = floatval($oldMoney) - $money;
        if ($newMoney < 0) {
            return false;
        }
        $log = self::log($user_id, $type, 0, -$money, $newMoney, $remark, 0);
        if (empty($log)) {
            return false;
        }
        $oldMoneyTo = UserModel::query()->where('id', $toUser)
            ->value('money');
        $newMoneyTo = floatval($oldMoneyTo) + $money;
        $logTo = self::log($toUser, $type, 0, $money, $newMoneyTo, $remark, 0);
        UserModel::query()->where('id', $user_id)->update([
            'money' => $newMoney
        ]);
        UserModel::query()->where('id', $toUser)->update([
            'money' => $newMoneyTo
        ]);
        $model = call_user_func($cb, $log, $logTo);
        if ($model === false) {
            $log->refund();
            $logTo->refund();
            return false;
        }
        if (is_numeric($model)) {
            $logTo->item_id = $log->item_id = $model;
        } elseif (isset($model['id'])) {
            $logTo->item_id = $log->item_id = $model['id'];
        }
        $log->paid();
        $logTo->paid();
        return $model;
    }

    public static function isBought(int $id, int $type = self::TYPE_DEFAULT): bool {
        return AccountLogModel::where('item_id', $id)
                ->where('type', $type)->count() > 0;
    }
}