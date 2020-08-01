<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Concerns;

/**
 * 管理员权限控制器
 * @package Module\Auth\Domain\Concerns
 */
trait AdminRole {

    use CheckRole;

    protected function rules() {
        return [
            '*' => 'administrator'
        ];
    }
}