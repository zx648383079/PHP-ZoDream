<?php
declare(strict_types=1);
namespace Module\Wallet\Domain\Repositories;

use Domain\Constants;
use Domain\Model\ModelHelper;
use Domain\Model\SearchModel;
use Module\Wallet\Domain\Entities\WalletEntity;
use Exception;
use Module\Auth\Domain\Events\ManageAction;
use Module\Wallet\Domain\FundAccount;
use Module\Wallet\Domain\Models\AccountLogModel;

final class WalletRepository {


    public static function get(int $user) {
        return WalletEntity::where('id', $user)->first('money', 'credits');
    }

    public static function logList(string $keywords = '', string|int $type = '', int $user = 0) {
        $items = ModelHelper::parseArrInt($type);
        return AccountLogModel::with('user')
            ->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['remark']);
            })->when(!empty($items), function ($query) use ($items) {
                $query->whereIn('type', $items);
            })
            ->orderBy('created_at', 'desc')->page();
    }

    /**
     * 充值账户
     * @param int $user_id
     * @param float $money
     * @param string $remark
     * @param int $type
     * @throws Exception
     */
    public static function recharge(int $user_id, float $money, string $remark, int $type = 0) {
        $money = abs($money);
        if ($money <= 0) {
            throw new Exception('金额输入不正确');
        }
        if ($type > 0) {
            $money *= -1;
        }
        if (!FundAccount::change($user_id,
            FundAccount::TYPE_ADMIN, auth()->id(), $money, $remark, 1)) {
            throw new Exception('操作失败，金额不足');
        }
        event(new ManageAction('user_recharge',
            sprintf('充值金额：%d', $money)
            , Constants::TYPE_USER_RECHARGE, $user_id));
    }
}