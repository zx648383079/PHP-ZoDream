<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Repositories;

use Module\Auth\Domain\Model\AccountLogModel;
use Module\Auth\Domain\Model\LoginLogModel;
use Module\Auth\Domain\Model\OAuthModel;
use Module\Auth\Domain\Model\RBAC\PermissionModel;
use Module\Auth\Domain\Model\RBAC\RoleModel;
use Module\Auth\Domain\Model\RBAC\RolePermissionModel;
use Module\Auth\Domain\Model\RBAC\UserRoleModel;
use Module\Auth\Domain\Model\UserModel;
use Module\Auth\Domain\Model\UserSimpleModel;

final class StatisticsRepository {
    public static function subtotal(): array {
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        $yesterdayStart = $todayStart - 86400;
        $user_count = UserModel::query()->count();
        $user_today = $user_count < 1 ? 0 : UserModel::where('created_at', '>=', $todayStart)->count();
        $user_yesterday = $user_count < 1 ? 0 : UserModel::where('created_at', '<', $todayStart)
            ->where('created_at', '>=', $yesterdayStart)->count();
        $money_total = AccountLogModel::query()->sum('money');
        $money_today = AccountLogModel::where('created_at', '>=', $todayStart)->sum('money');
        $money_yesterday = AccountLogModel::where('created_at', '<', $todayStart)
            ->where('created_at', '>=', $yesterdayStart)->sum('money');

        $login_today = LoginLogModel::where('status', 1)->where('created_at', '>=', $todayStart)
            ->groupBy('user_id')
            ->count();
        $login_yesterday = LoginLogModel::where('status', 1)->where('created_at', '<', $todayStart)
            ->where('created_at', '>=', $yesterdayStart)
            ->groupBy('user_id')
            ->count();
        return compact('user_count', 'user_today', 'user_yesterday',
            'money_today', 'money_total', 'money_yesterday', 'login_today', 'login_yesterday');
    }

    public static function user(int $id, string $extra = ''): array {
        $user = UserRepository::get($id);
        if ($extra === 'count') {
            return [
                'data' => self::userCount($id)
            ];
        }
        $data = $user->toArray();
        if ($user->parent_id > 0) {
            $data['parent'] = UserSimpleModel::find($user->parent_id);
        }
        $data['oauth_count'] = OAuthModel::where('user_id', $user->id)->count();
        $data['last_login'] = LoginLogModel::where('user_id', $user->id)
            ->where('status', 1)->orderBy('created_at', 'desc')
            ->first();
        $data['login_ip'] = LoginLogModel::where('user_id', $user->id)
            ->where('status', 1)->groupBy('ip')
            ->selectRaw('ip,COUNT(*) as count')->orderBy('count', 'desc')
            ->first();
        $data['card_items'] = CardRepository::getUserCard($user->id);
        $roleId = UserRoleModel::where('user_id', $user->id)->pluck('role_id');
        $roleItems = empty($roleId) ? [] : RoleModel::whereIn('id', $roleId)->asArray()->get('name', 'display_name');
        $data['role_items'] = [];
        $isAdministrator = false;
        foreach ($roleItems as $item) {
            $data['role_items'][] = $item['display_name'];
            if ($item['name'] === 'administrator') {
                $isAdministrator = true;
            }
        }
        if ($isAdministrator) {
            $data['permission_items'] = PermissionModel::query()->pluck('display_name');
        } else {
            $permissionId = empty($roleId) ? [] : RolePermissionModel::whereIn('role_id', $roleId)->pluck('permission_id');
            $data['permission_items'] = empty($permissionId) ? [] : PermissionModel::whereIn('id', $permissionId)->pluck('display_name');
        }
        return $data;
    }

    public static function userCount(int $user): array {
        $maps = [
            'Module\\AppStore' => '',
            'Module\\Blog' => '',
            'Module\\Book' => '',
            'Module\\Document' => '',
            'Module\\Forum' => '',
            'Module\\MicroBlog' => '',
            'Module\\Note' => '',
            'Module\\ResourceStore' => '',
            'Module\\Shop' => '',
        ];
        $data = [];
        $modules = config('route.modules');
        $exist = [];
        foreach ($modules as $path => $module) {
            if (empty($module)) {
                continue;
            }
            if (in_array($module, $exist) || !array_key_exists($module, $maps)) {
                continue;
            }
            $exist[] = $module;
            $func = $maps[$module];
            if (empty($func)) {
                $func = sprintf('%s\\Domain\\Repositories\\StatisticsRepository::userCount', $module);
            }
            if (!is_callable($func)) {
                continue;
            }
            try {
                $data = array_merge($data, call_user_func($func, $user));
            } catch (\Exception $ex) {}
        }
        return array_values($data);
    }
}