<?php
declare(strict_types=1);
namespace Module\Auth\Domain;

use Zodream\Infrastructure\Contracts\UserObject;

interface IAuthPlatform {

    public function id(): string|int;

    public function option(string $store, string|null $code = null): mixed;

    public function getCookieTokenKey(): string;

    public function generateToken(UserObject $user): string;
}