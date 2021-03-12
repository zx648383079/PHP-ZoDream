<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Model\Concerns;

use Exception;
use Module\Auth\Domain\Model\RBAC\RoleModel;
use Module\Auth\Domain\Model\RBAC\UserRoleModel;
use Module\Auth\Domain\Repositories\UserRepository;
use Zodream\Helpers\Str;

trait UserRoleTrait {

    public function roles() {
        return $this->belongsToMany(RoleModel::class,
            UserRoleModel::class,
            'user_id',
            'role_id');
    }

    public function getRoleIdsAttribute() {
        if ($this->id < 1) {
            return [];
        }
        return UserRoleModel::where('user_id', $this->id)->pluck('role_id');
    }

    /**
     * 验证用户是否具有指定角色身份
     *
     * @param string|array $name Role name or array of role names.
     * @param bool $requireAll All roles in the array are required.
     *
     * @return bool
     * @throws Exception
     */
    public function hasRole(array|string $name, bool $requireAll = false): bool {
        if (is_array($name)) {
            foreach ($name as $roleName) {
                $hasRole = $this->hasRole($roleName);
                if ($hasRole && !$requireAll) {
                    return true;
                } elseif (!$hasRole && $requireAll) {
                    return false;
                }
            }
            return $requireAll;
        }
        $data = UserRepository::rolePermission($this->id);
        return in_array($name, $data['roles']);
    }

    /**
     * 验证用户是否具有指定操作的权限
     *
     * @param string|array $permission Permission string or array of permissions.
     * @param bool $requireAll All permissions in the array are required.
     *
     * @return bool
     * @throws Exception
     */
    public function can(string|array $permission, bool $requireAll = false): bool {
        if (is_array($permission)) {
            foreach ($permission as $permName) {
                $hasPerm = $this->can($permName);
                if ($hasPerm && !$requireAll) {
                    return true;
                } elseif (!$hasPerm && $requireAll) {
                    return false;
                }
            }
            return $requireAll;
        }
        $data = UserRepository::rolePermission($this->id);
        foreach ($data['permissions'] as $perm) {
            if (Str::is($permission, $perm) ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checks role(s) and permission(s).
     *
     * @param string|array $roles Array of roles or comma separated string
     * @param string|array $permissions Array of permissions or comma separated string.
     * @param bool $validateAll 是否要验证所有
     * @param int $returnType 返回值类型
     * 0 为 bool 验证是否通过
     * 1 为  [
     *          roles => [role => bool],
     *          permissions => [permission => bool]
     * ]
     *
     * 2 为 [&0, &1]
     * @return array|bool
     * @throws Exception
     */
    public function ability(
        string|array $roles,
        string|array $permissions,
        bool $validateAll = false, int $returnType = 0): array|bool {
        // Convert string to array if that's what is passed in.
        if (!is_array($roles)) {
            $roles = explode(',', $roles);
        }
        if (!is_array($permissions)) {
            $permissions = explode(',', $permissions);
        }
        // Loop through roles and permissions and check each.
        $checkedRoles = [];
        $checkedPermissions = [];
        foreach ($roles as $role) {
            $checkedRoles[$role] = $this->hasRole($role);
        }
        foreach ($permissions as $permission) {
            $checkedPermissions[$permission] = $this->can($permission);
        }
        // If validate all and there is a false in either
        // Check that if validate all, then there should not be any false.
        // Check that if not validate all, there must be at least one true.
        if(($validateAll && !(in_array(false,$checkedRoles) || in_array(false,$checkedPermissions))) ||
            (!$validateAll && (in_array(true,$checkedRoles) || in_array(true,$checkedPermissions)))) {
            $validateAll = true;
        } else {
            $validateAll = false;
        }
        // Return based on option
        if ($returnType < 1) {
            return $validateAll;
        }
        if ($returnType === 1) {
            return ['roles' => $checkedRoles, 'permissions' => $checkedPermissions];
        }
        return [$validateAll, ['roles' => $checkedRoles, 'permissions' => $checkedPermissions]];
    }

    /**
     * 是否是管理员
     * @return bool
     * @throws Exception
     */
    public function isAdministrator(): bool
    {
        return $this->hasRole('administrator');
    }

}