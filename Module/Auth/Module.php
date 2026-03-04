<?php
namespace Module\Auth;

use Domain\Heartbeat;
use Module\Auth\Domain\Migrations\CreateAuthTables;
use Module\Auth\Domain\Repositories\UserRepository;
use Zodream\Route\Controller\Concerns\UseModulePackage;
use Zodream\Route\Controller\Module as BaseModule;

class Module extends BaseModule implements Heartbeat {

    use UseModulePackage;

    public function getMigration() {
        return new CreateAuthTables();
    }


    public function pulse(int $delta): array {
        if (auth()->guest()) {
            return [];
        }
        return [
            'bulletin_count' => UserRepository::getBulletinCount(auth()->id())
        ];
    }
}