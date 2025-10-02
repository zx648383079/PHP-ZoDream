<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Repositories;

use Domain\Constants;
use Domain\Model\ModelHelper;
use Domain\Model\SearchModel;
use Exception;
use Infrastructure\Ip;
use Module\Auth\Domain\Entities\UserEntity;
use Module\Auth\Domain\Events\CancelAccount;
use Module\Auth\Domain\Events\ManageAction;
use Module\Auth\Domain\Helpers;
use Module\Auth\Domain\Model\Bulletin\BulletinUserModel;
use Module\Auth\Domain\Model\LoginLogModel;
use Module\Auth\Domain\Model\RBAC\UserRoleModel;
use Module\Auth\Domain\Model\UserMetaModel;
use Module\Auth\Domain\Model\UserModel;
use Module\Auth\Domain\Model\UserSimpleModel;
use Module\Blog\Domain\Repositories\BlogRepository;
use Module\Game\CheckIn\Domain\Repositories\CheckinRepository;
use Zodream\Helpers\Str;
use Zodream\Html\Page;

class UserRepository {

    /**
     * 判断账户是否已实名认证
     * @param UserModel|array|null $user
     * @return bool
     */
    public static function isVerified(UserModel|array|null $user): bool {
        return !empty($user) && $user['status'] == UserModel::STATUS_ACTIVE_VERIFIED;
    }

    /**
     * 判断账户是否激活
     * @param UserModel|array|null $user
     * @return bool
     */
    public static function isActive(UserModel|array|null $user): bool {
        return !empty($user) && $user['status'] >= UserModel::STATUS_ACTIVE;
    }

    public static function getPublicProfile(int $id, string $extra = ''): array {
        $user = UserModel::where('id', $id)
            ->first(['id', 'name', 'avatar', 'mobile', 'email', 'sex', 'status', 'created_at']);
        if (empty($user)) {
            throw new Exception(__('Not found user'));
        }
        $data = static::format($user, true);
        unset($data['status']);
        return self::appendExtraData($data, $extra);
    }

    public static function getCurrentProfile(string $extra = ''): array|null {
        if (auth()->guest()) {
            return null;
        }
        /** @var UserModel $user */
        $user = auth()->user();
        $data = static::format($user);
        $data['country'] = Ip::country(request()->ip());
        $data['is_admin'] = $user->isAdministrator() || $user->hasRole('shop_admin');
        return self::appendExtraData($data, $extra);
    }

    private static function appendExtraData(array $data, string $extra = '') {
        if (empty($extra)) {
            return $data;
        }
        $userId = intval($data['id']);
        $extraWords = explode(',', $extra);
        foreach ([
            'last_ip',
            'card_items',
             'bulletin_count',
             'today_checkin',
             'post_count',
             'following_count',
             'follower_count',
                 ] as $word) {
            if (!in_array($word, $extraWords)) {
                continue;
            }
            $func = sprintf('%s::get%s',
                UserRepository::class, Str::studly($word));
            if (!is_callable($func)) {
                continue;
            }
            $data[$word] = call_user_func($func, $userId);
        }
        if (RelationshipRepository::containsRelationship($extraWords)) {
            $data = array_merge($data, RelationshipRepository::relationship(auth()->id(), $userId));
        }
        return $data;
    }

    public static function getLastIp(int $user): string {
         return Helpers::hideIp((string)LoginLogModel::where('user_id', $user)
             ->where('status', 1)->orderBy('created_at', 'desc')
             ->value('ip'));
    }

    public static function getCardItems(int $user): array {
        return CardRepository::getUserCard($user);
    }

    public static function getBulletinCount(int $user): int {
        return BulletinUserModel::where('user_id', $user)->where('status', 0)->count();
    }

    public static function getPostCount(int $user): int {
        return BlogRepository::getPostCount($user);
    }

    public static function getFollowingCount(int $user): int {
        return RelationshipRepository::followingCount($user);
    }

    public static function getFollowerCount(int $user): int {
        return RelationshipRepository::followerCount($user);
    }

    public static function getTodayCheckin(int $user): bool {
        return CheckinRepository::todayIsChecked($user);
    }

    public static function format(UserEntity|UserModel|array $user, bool $hide = true): array {
        $data = is_array($user) ? $user : $user->toArray();
        if ($hide) {
            $data['email'] = AuthRepository::isEmptyEmail($data['email']) ? '' : Helpers::hideEmail($data['email']);
            $data['mobile'] = Helpers::hideTel($data['mobile']);
        }
        $data['is_verified'] = static::isVerified($user);
        return $data;
    }

    public static function getAll(string $keywords = '', string $sort = 'id', string|bool|int $order = 'desc') {
        list($sort, $order) = SearchModel::checkSortOrder($sort, $order, [
           'id', 'created_at',
            'name', 'email',
            'status', 'sex', 'money', 'credits'
        ]);

        /** @var Page $page */
        $page = UserModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'name');
        })->orderBy($sort, $order)->page();
        return $page->map(function ($item) {
            return static::format($item, false);
        });
    }

    public static function searchUser(string $keywords = '') {
        return UserSimpleModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'name');
        })->orderBy('id', 'desc')->page();
    }

    /**
     * @param int $id
     * @return bool|UserModel
     * @throws Exception
     */
    public static function get(int $id) {
        $model = UserModel::findIdentity($id);
        if (empty($model)) {
            throw new Exception('会员不存在');
        }
        return $model;
    }

    public static function saveIDCard(int $id, string $idCard = ''): void {
        UserMetaModel::saveBatch($id, [
            'id_card' => $idCard
        ]);
        UserModel::where('id', $id)->where('status', '>=', UserModel::STATUS_ACTIVE)->update([
           'status' => empty($idCard) ? UserModel::STATUS_ACTIVE : UserModel::STATUS_ACTIVE_VERIFIED
        ]);
    }

    /**
     * 保存用户
     * @param array $data
     * @param array $roles
     * @return UserModel
     * @throws Exception
     */
    public static function save(array $data, array $roles) {
        $id = isset($data['id']) && $data['id'] > 0 ? intval($data['id']) : 0;
        $rule = $id > 0 ? [
            'name' => 'required|string',
            'email' => 'required|email',
            'sex' => 'int',
            'avatar' => 'string',
            'birthday' => 'string',
            'password' => 'string',
        ] : [
            'name' => 'required|string',
            'email' => 'required|email',
            'sex' => 'int',
            'avatar' => 'string',
            'birthday' => 'string',
            'password' => 'required|string',
        ];
        if ($data['password'] != $data['confirm_password']) {
            throw new Exception('两次密码不一致！');
        }
        if (empty($data['password'])) {
            unset($data['password'], $data['confirm_password']);
        }
        $model = UserModel::findOrNew($id);
        if (!$model->load($data) || !$model->validate($rule)) {
            throw new Exception($model->getFirstError());
        }
        if (!empty($data['password'])) {
            $model->setPassword($data['password']);
        }
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        static::saveRoles($model->id, $roles);
        event(new ManageAction('user_edit', $model->name, Constants::TYPE_USER_UPDATE, $model->id));
        return $model;
    }

    public static function saveRoles(int $user, array $roles) {
        list($add, $_, $del) = ModelHelper::splitId($roles,
            UserRoleModel::where('user_id', $user)->pluck('role_id')
        );
        if (!empty($del)) {
            UserRoleModel::where('user_id', $user)
                ->whereIn('role_id', $del)
                ->delete();
        }
        if (!empty($add)) {
            UserRoleModel::query()->insert(array_map(function ($id) use ($user) {
                return [
                    'user_id' => $user,
                    'role_id' => $id
                ];
            }, $add));
        }
    }

    public static function remove(int $id) {
        if ($id == auth()->id()) {
            throw new Exception('不能删除自己！');
        }
        $user = UserModel::find($id);
        $user->delete();
        event(new CancelAccount($user, time()));
        event(new ManageAction('user_remove', $user->name, 5, $user->id));
    }

    public static function manageDetail(int $user): array
    {
        $model = self::get($user);
        $data = $model->toArray();
        $data['roles'] = UserRoleModel::where('user_id', $user)->pluck('role_id');
        $data['zone_id'] = intval(UserMetaModel::where('user_id', $user)
            ->where('name', 'zone_id')->value('content'));
        return $data;
    }

    /**
     * 缓存用户的权限
     * @param int $user
     * @return array [role => array, roles => string[], permissions => string[]]
     * @throws Exception
     */
    public static function rolePermission(int $user): array {
        return cache()
            ->getOrSet('user_role_permission_'.$user, function () use ($user) {
                return RoleRepository::userRolePermission($user);
            }, 600);
    }

    public static function getName(int $id) {
        return UserModel::where('id', $id)->value('name');
    }

    /**
     * 获取用户id
     * @param string $keywords
     * @param array $userId
     * @param bool $checkEmpty true 为先判断 $userId 是否为空
     * @return array
     */
    public static function searchUserId(string $keywords, array $userId = [], bool $checkEmpty = false): array {
        if (empty($keywords)) {
            return $userId;
        }
        if ($checkEmpty && empty($userId)) {
            return [];
        }
        $query = SearchModel::searchWhere(UserModel::query(), ['name'], false, '', $keywords);
        if (!empty($userId)) {
            $query->whereIn('id', $userId);
        }
        return $query->pluck('id');
    }
}