<?php
namespace Module\Shop\Domain\Repositories;

use Module\Auth\Domain\Model\OAuthModel;

class AccountRepository {


    public static function getConnect() {
        $map_list = self::getConnectMaps();
        $model_list = OAuthModel::where('user_id', auth()->id())
            ->get('id', 'vendor', 'nickname', 'created_at');
        foreach ($model_list as $item) {
            $item = $item->toArray();
            if (isset($map_list[$item['vendor']])) {
                $map_list[$item['vendor']] = array_merge($map_list[$item['vendor']], $item);
            }
        }
        return $map_list;
    }

    private static function getConnectMaps() {
        return [
            'qq' => [
                'name' => 'QQ',
                'icon' => 'fa-qq',
            ],
            'wx' => [
                'name' => '微信',
                'icon' => 'fa-weixin',
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