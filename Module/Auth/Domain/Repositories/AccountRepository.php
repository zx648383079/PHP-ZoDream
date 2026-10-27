<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Repositories;

use Domain\Model\SearchModel;
use Infrastructure\LinkRule;
use Module\Auth\Domain\Entities\ActionLogEntity;
use Module\Auth\Domain\Entities\AdminLogEntity;
use Module\Auth\Domain\Entities\LoginLogEntity;
use Module\Auth\Domain\Entities\OAuthEntity;
use Module\Auth\Domain\Model\UserModel;
use Exception;
use Module\OpenPlatform\Domain\Platform;

final class AccountRepository {


    public static function loginLog(string $keywords = '', int $user = 0) {
        return LoginLogEntity::when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'ip');
            })
            ->orderBy('id', 'desc')->page();
    }

    /**
     * 纪录登录记录
     * @param string $user 登录邮箱
     * @param int $user_id
     * @param bool $status 成功或失败
     * @param string $mode 页面登录或其他
     * @return LoginLogEntity
     * @throws \Exception
     */
	public static function addLoginLog(string $user, int $user_id = 0, bool $status = false, string $mode = LoginLogEntity::MODE_WEB) {
		return LoginLogEntity::create([
            'ip' => request()->ip(),
            'user' => $user,
            'user_id' => intval($user_id),
            'status' => $status ? 1 : 0,
            'mode' => $mode,
            'created_at' => time()
        ]);
	}

    /**
     * 今天失败登录的次数
     * @param string $ip
     * @param integer $time
     * @return integer
     */
	public static function failureCount(string $ip, int $time) {
	    return LoginLogEntity::where('ip', $ip)
                ->where('status', 0)
                ->where('created_at', '>=', $time)->count();
    }

    public static function actionLog(string $keywords = '', int $user = 0) {
        return ActionLogEntity::when($user > 0, function ($query) use ($user) {
            $query->where('user_id', $user);
        })
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'ip');
            })
            ->orderBy('id', 'desc')->page();
    }

    /**
     * 用户操作记录
     * @param int $user_id
     * @param $action
     * @param string $remark
     * @return ActionLogEntity
     * @throws \Exception
     */
	public static function addActionLog(int $user_id, string $action, string $remark = '') {
		return ActionLogEntity::create([
            'ip' => request()->ip(),
            'user_id' => $user_id,
            'action' => $action,
            'remark' => $remark,
            'created_at' => time()
        ]);
	}

    public static function adminLog(string $keywords = '', int $user = 0) {
        return AdminLogEntity::with('user')
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['action', 'remark']);
            })
            ->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })->orderBy('id', 'desc')
            ->page();
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
        $model_list = OAuthEntity::where('user_id', auth()->id())
            ->get('id', 'vendor', 'nickname', 'platform_id', 'created_at');
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
        if (!empty($keys[OAuthEntity::TYPE_WEBAUTHN]) &&
            !in_array($platformId, $keys[OAuthEntity::TYPE_WEBAUTHN])) {
            $items[] = [
                'vendor' => OAuthEntity::TYPE_WEBAUTHN,
                'name' => $map_list[OAuthEntity::TYPE_WEBAUTHN]['name'],
                'icon' => $map_list[OAuthEntity::TYPE_WEBAUTHN]['icon'],
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
            OAuthEntity::TYPE_QQ => [
                'name' => 'QQ',
                'icon' => 'fa-qq',
            ],
            OAuthEntity::TYPE_WX => [
                'name' => '微信',
                'icon' => 'fa-wechat',
            ],
            OAuthEntity::TYPE_WX_MINI => [
                'name' => '微信小程序',
                'icon' => 'fa-wechat',
            ],
            'alipay' => [
                'name' => '支付宝',
                'icon' => 'fa-alipay',
            ],
            OAuthEntity::TYPE_WEIBO => [
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
            OAuthEntity::TYPE_WEBAUTHN => [
                'name' => 'WebAuthn',
                'icon' => 'fa-fingerprint',
            ],
            OAuthEntity::TYPE_2FA => [
                'name' => '两步验证',
                'icon' => 'fa-mobile',
            ],
        ];
    }
}