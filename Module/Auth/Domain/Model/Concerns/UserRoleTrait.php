<?php
namespace Module\Auth\Domain\Model\Concerns;


use Module\Auth\Domain\Model\RBAC\RoleModel;
use Module\Auth\Domain\Model\RBAC\UserRoleModel;
use Zodream\Database\Relation;
use Zodream\Helpers\Str;

trait UserRoleTrait {

    /**
     * Big block of caching functionality.
     *
     * @return RoleModel[]
     * @throws \Exception
     */
    public function cachedRoles() {
        $cacheKey = 'auth_roles_for_user_'.$this->id;
        return cache()->getOrSet($cacheKey, function () {
                $ids = $this->role_ids;
                if (empty($ids)) {
                    return [];
                }
                return RoleModel::whereIn('id', $ids)->get();
//            return $this->roles()->get();
        }, 60);
    }

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
     * 保存角色
     * @param $roles
     */
    public function setRole($roles) {
        $data = [];
        foreach ((array)$roles as $id) {
            $id = intval($id);
            if ($id < 1 || in_array($id, $data)) {
                continue;
            }
            $data[] = $id;
        }
        if (empty($data) && empty($this->role_ids)) {
            return;
        }
        $diff = empty($data) ? [] : array_diff($data, $this->role_ids);
        $del_diff = empty($this->role_ids) ? [] : array_diff($this->role_ids, $data);
        if (!empty($del_diff)) {
            UserRoleModel::where('user_id', $this->id)
                ->whereIn('role_id', $del_diff)
                ->delete();
        }
        if (empty($diff)) {
            return;
        }
        UserRoleModel::query()->insert(array_map(function ($id) {
            return [
                'user_id' => $this->id,
                'role_id' => $id
            ];
        }, $diff));
    }

    /**
     * Checks if the user has a role by its name.
     *
     * @param string|array $name       Role name or array of role names.
     * @param bool         $requireAll All roles in the array are required.
     *
     * @return bool
     */
    public function hasRole($name, $requireAll = false) {
        if (is_array($name)) {
            foreach ($name as $roleName) {
                $hasRole = $this->hasRole($roleName);
                if ($hasRole && !$requireAll) {
                    return true;
                } elseif (!$hasRole && $requireAll) {
                    return false;
                }
            }
            // If we've made it this far and $requireAll is FALSE, then NONE of the roles were found
            // If we've made it this far and $requireAll is TRUE, then ALL of the roles were found.
            // Return the value of $requireAll;
            return $requireAll;
        } else {
            foreach ($this->cachedRoles() as $role) {
                if ($role->name == $name) {
                    return true;
                }
            }
        }
        return false;
    }
    /**
     * Check if user has a permission by its name.
     *
     * @param string|array $permission Permission string or array of permissions.
     * @param bool         $requireAll All permissions in the array are required.
     *
     * @return bool
     */
    public function can($permission, $requireAll = false) {
        if (is_array($permission)) {
            foreach ($permission as $permName) {
                $hasPerm = $this->can($permName);
                if ($hasPerm && !$requireAll) {
                    return true;
                } elseif (!$hasPerm && $requireAll) {
                    return false;
                }
            }
            // If we've made it this far and $requireAll is FALSE, then NONE of the perms were found
            // If we've made it this far and $requireAll is TRUE, then ALL of the perms were found.
            // Return the value of $requireAll;
            return $requireAll;
        } else {
            foreach ($this->cachedRoles() as $role) {
                // Validate against the Permission table
                foreach ($role->cachedPermissions() as $perm) {
                    if (Str::is( $permission, $perm->name) ) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
    /**
     * Checks role(s) and permission(s).
     *
     * @param string|array $roles       Array of roles or comma separated string
     * @param string|array $permissions Array of permissions or comma separated string.
     * @param array        $options     validate_all (true|false) or return_type (boolean|array|both)
     *
     * @throws \InvalidArgumentException
     *
     * @return array|bool
     */
    public function ability($roles, $permissions, $options = []) {
        // Convert string to array if that's what is passed in.
        if (!is_array($roles)) {
            $roles = explode(',', $roles);
        }
        if (!is_array($permissions)) {
            $permissions = explode(',', $permissions);
        }
        // Set up default values and validate options.
        if (!isset($options['validate_all'])) {
            $options['validate_all'] = false;
        } else {
            if ($options['validate_all'] !== true && $options['validate_all'] !== false) {
                throw new \InvalidArgumentException();
            }
        }
        if (!isset($options['return_type'])) {
            $options['return_type'] = 'boolean';
        } else {
            if ($options['return_type'] != 'boolean' &&
                $options['return_type'] != 'array' &&
                $options['return_type'] != 'both') {
                throw new \InvalidArgumentException();
            }
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
        if(($options['validate_all'] && !(in_array(false,$checkedRoles) || in_array(false,$checkedPermissions))) ||
            (!$options['validate_all'] && (in_array(true,$checkedRoles) || in_array(true,$checkedPermissions)))) {
            $validateAll = true;
        } else {
            $validateAll = false;
        }
        // Return based on option
        if ($options['return_type'] == 'boolean') {
            return $validateAll;
        }
        if ($options['return_type'] == 'array') {
            return ['roles' => $checkedRoles, 'permissions' => $checkedPermissions];
        }
        return [$validateAll, ['roles' => $checkedRoles, 'permissions' => $checkedPermissions]];
    }

    /**
     * 是否是管理员
     * @return bool
     */
    public function isAdministrator() {
        return $this->hasRole('administrator');
    }

}