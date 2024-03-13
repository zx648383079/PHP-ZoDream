<?php
declare(strict_types=1);
namespace Module\Bot\Domain\Adapters;

use Module\Auth\Domain\Model\OAuthModel;
use Module\Auth\Domain\Model\UserModel;
use Module\Bot\Domain\MessageReply;
use Module\Bot\Domain\Model\BotModel;
use Zodream\ThirdParty\ThirdParty;
use Zodream\Infrastructure\Contracts\Http\Output;

abstract class BasePlatformAdapter implements IPlatformAdapter {

    protected array $cacheData = [];

    public function __construct(
        protected BotModel $platform)
    {
    }

    public function platformId(): int {
        return $this->platform->id;
    }

    public function listen(): Output {
        $message = $this->receive();
        if ($message['event'] === AdapterEvent::AccessVerification) {
            $this->platform->status = BotModel::STATUS_ACTIVE;
            $this->platform->save();
            return $this->replyAccessVerification();
        }
        $data = $this->tryReply($message);
        $output = response();
        $this->reply($output, $data);
        return $output;
    }

    abstract protected function replyAccessVerification(): Output;

    protected function tryReply(array $message): array {
        return (new MessageReply($this, $message))->reply();
    }

    public function authUser(string $openId): ?UserModel {
        $userId = $this->authUserId($openId);
        if ($userId < 1) {
            return null;
        }
        return $this->getOrSet(sprintf('user_%d', $userId), function () use ($userId) {
            return UserModel::findByIdentity($userId);
        });
    }
    public function authUserId(string $openId): int {
        return $this->getOrSet(sprintf('user_id_%s', $openId), function () use ($openId) {
            return OAuthModel::findUserId($openId, $this->oAuthType());
        });
    }

    /**
     * 获取并进行数据缓存
     * @param string $func
     * @param callable|mixed $callback
     * @return mixed
     */
    public function getOrSet(string $func, mixed $callback): mixed {
        if (isset($this->cacheData[$func])
            || array_key_exists($func, $this->cacheData)) {
            return $this->cacheData[$func];
        }
        return $this->cacheData[$func] = is_callable($callback) ?
            call_user_func($callback) : $callback;
    }

    protected function formatConfig(): array {
        return [
            'appid' => $this->platform->appid,
            'secret' => $this->platform->secret,
            'aes_key' => $this->platform->aes_key,
            'token' => $this->platform->token
        ];
    }

    /**
     * 注入sdk
     * @param string|mixed $instance
     * @return ThirdParty
     * @throws \Exception
     */
    protected function sdk(string|ThirdParty $instance) {
        if ($instance instanceof ThirdParty) {
            return $instance->set($this->formatConfig());
        }
        if (class_exists($instance)) {
            return new $instance($this->formatConfig());
        }
        throw new \Exception('sdk is error');
    }

    protected function cacheSdk(string $instance) {
        return $this->getOrSet($instance, function () use ($instance) {
            return $this->sdk($instance);
        });
    }
}