<?php
namespace Module\OpenPlatform\Domain\Listeners;


use Module\Auth\Domain\Events\TokenCreated;
use Module\OpenPlatform\Domain\Model\PlatformModel;
use Module\OpenPlatform\Domain\Model\UserTokenModel;
use Zodream\Service\Factory;

class TokenListener {

    public function __construct(TokenCreated $token) {
        /** @var PlatformModel $platform */
        $platform = app('platform');
        if (empty($platform)) {
            return;
        }
        UserTokenModel::create([
            'user_id' => $token->getUser()->id,
            'platform_id' => $platform->id,
            'token' => $token->getToken(),
            'expired_at' => $token->getExpiredAt(),
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }
}
