<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Repositories;

use Domain\Constants;
use Domain\Model\ModelHelper;
use Domain\Model\SearchModel;
use Infrastructure\LinkRule;
use Module\Auth\Domain\Events\ManageAction;
use Module\Auth\Domain\FundAccount;
use Module\Auth\Domain\Model\AccountLogModel;
use Module\Auth\Domain\Model\ActionLogModel;
use Module\Auth\Domain\Model\AdminLogModel;
use Module\Auth\Domain\Model\LoginLogModel;
use Module\Auth\Domain\Model\OAuthModel;
use Module\Auth\Domain\Model\UserModel;
use Exception;
use Module\OpenPlatform\Domain\Platform;

final class AccountRepository {

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

    public static function loginLog(string $keywords = '', int $user = 0) {
        return LoginLogModel::when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'ip');
            })
            ->orderBy('id', 'desc')->page();
    }

    public static function actionLog(string $keywords = '', int $user = 0) {
        return ActionLogModel::when($user > 0, function ($query) use ($user) {
            $query->where('user_id', $user);
        })
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'ip');
            })
            ->orderBy('id', 'desc')->page();
    }

    public static function adminLog(string $keywords = '', int $user = 0) {
        return AdminLogModel::with('user')
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['action', 'remark']);
            })
            ->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })->orderBy('id', 'desc')
            ->page();
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

    public static function cancel(UserModel $user, string $reason) {
        $user->status = UserModel::STATUS_FROZEN;
        $user->save();
        BulletinRepository::sendAdministrator('账户注销申请',
            sprintf('申请用户：%s，注销理由：%s [马上查看]', $user->name,
                $reason), 98,
            [
                LinkRule::formatLink('[马上查看]', 'b/user/'. $user->id)
            ]
        );
        return $user;
    }

    public static function getConnect(): array {
        $map_list = self::getConnectMaps();
        $model_list = OAuthModel::where('user_id', auth()->id())
            ->get('id', 'vendor', 'nickname', 'created_at');
        $platformId = Platform::platformId();
        $items = [];
        $keys = [];
        foreach ($model_list as $item) {
            $data = $item->toArray();
            if (isset($map_list[$item['vendor']])) {
                $items[] = array_merge($map_list[$item['vendor']], $data, [
                    'platform' => $item['platform_id'] < 1 ? '主站' : ($item['platform_id'] === $platformId ?
                        '当前' : '其他')
                ]);
            }
            $keys[$item['vendor']][] = $item['platform_id'];
        }
        if (!empty($keys[OAuthModel::TYPE_WEBAUTHN]) &&
            !in_array($platformId, $keys[OAuthModel::TYPE_WEBAUTHN])) {
            $items[] = [
                'vendor' => OAuthModel::TYPE_WEBAUTHN,
                'name' => $map_list[OAuthModel::TYPE_WEBAUTHN]['name'],
                'icon' => $map_list[OAuthModel::TYPE_WEBAUTHN]['icon'],
                'platform' => '当前'
            ];
        }
        foreach ($map_list as $vendor => $item) {
            if (isset($keys[$vendor])) {
                continue;
            }
            $item['vendor'] = $vendor;
            $items[] = $item;
        }
        return $items;
    }

    public static function getDriver(): array {
        return [
        ];
    }

    public static function getAuthorizeApp(): array {
        return [
        ];
    }

    private static function getConnectMaps(): array {
        return [
            OAuthModel::TYPE_QQ => [
                'name' => 'QQ',
                'icon' => 'fa-qq',
            ],
            OAuthModel::TYPE_WX => [
                'name' => '微信',
                'icon' => 'fa-wechat',
            ],
            OAuthModel::TYPE_WX_MINI => [
                'name' => '微信小程序',
                'icon' => 'fa-wechat',
            ],
            'alipay' => [
                'name' => '支付宝',
                'icon' => 'fa-alipay',
            ],
            OAuthModel::TYPE_WEIBO => [
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
            OAuthModel::TYPE_WEBAUTHN => [
                'name' => 'WebAuthn',
                'icon' => 'fa-fingerprint',
            ],
            OAuthModel::TYPE_2FA => [
                'name' => '两步验证',
                'icon' => 'fa-mobile',
            ],
        ];
    }
}