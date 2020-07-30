<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Repositories;

use Module\Auth\Domain\Model\AccountLogModel;
use Module\Auth\Domain\Model\Bulletin\BulletinModel;
use Module\Auth\Domain\Model\OAuthModel;
use Module\Auth\Domain\Model\UserModel;
use Exception;

class AccountRepository {

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
        if (!AccountLogModel::change($user_id,
            AccountLogModel::TYPE_ADMIN, auth()->id(), $money, $remark, 1)) {
            throw new Exception('操作失败，金额不足');
        }
    }

    public static function cancel(UserModel $user, string $reason) {
        $user->status = UserModel::STATUS_FROZEN;
        $user->save();
        BulletinModel::system(1, '账户注销申请',
            sprintf('申请用户：%s，注销理由：%s <a href="%s">马上查看</a>', $user->name,
                $reason, url('/auth/admin/user/edit', ['id' => $user->id])), 98);
        return $user;
    }

    public static function getConnect(): array {
        $map_list = self::getConnectMaps();
        $model_list = OAuthModel::where('user_id', auth()->id())
            ->get('id', 'vendor', 'nickname', 'created_at');
        foreach ($model_list as $item) {
            $item = $item->toArray();
            if (isset($map_list[$item['vendor']])) {
                $map_list[$item['vendor']] = array_merge($map_list[$item['vendor']], $item);
            }
        }
        return array_values($map_list);
    }

    public static function getDriver(): array {
        return [
        ];
    }

    private static function getConnectMaps(): array {
        return [
            'qq' => [
                'name' => 'QQ',
                'icon' => 'fa-qq',
            ],
            'wx' => [
                'name' => '微信',
                'icon' => 'fa-wechat',
            ],
            'wx_mini' => [
                'name' => '微信小程序',
                'icon' => 'fa-wechat',
            ],
            'alipay' => [
                'name' => '支付宝',
                'icon' => 'fa-alipay',
            ],
            'weibo' => [
                'name' => '微博',
                'icon' => 'fa-weibo',
            ],
            'paypal' => [
                'name' => 'PayPal',
                'icon' => 'fa-paypal',
            ],
            'github' => [
                'name' => 'Github',
                'icon' => 'fa-github',
            ],
            'google' => [
                'name' => 'Google',
                'icon' => 'fa-google',
            ],
        ];
    }
}