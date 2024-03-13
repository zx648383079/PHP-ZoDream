<?php
declare(strict_types=1);
namespace Module\Bot\Domain\Adapters\Telegram;


use Module\Bot\Domain\Adapters\BasePlatformAdapter;
use Module\Bot\Domain\Adapters\IPlatformAdapter;
use Module\Bot\Domain\Model\MediaModel;
use Module\Bot\Domain\Model\QrcodeModel;
use Zodream\Infrastructure\Contracts\Http\Output;

class TelegramAdapter extends BasePlatformAdapter implements IPlatformAdapter {

    protected function replyAccessVerification(): Output {
        // TODO: Implement replyAccessVerification() method.
    }

    public function pullUser(string $openId): array {
        // TODO: Implement pullUser() method.
    }

    public function pullUsers(callable $cb) {
        // TODO: Implement pullUsers() method.
    }

    public function pushMenu(array $items) {
        // TODO: Implement pushMenu() method.
    }

    public function pushQr(QrcodeModel $model): array {
        // TODO: Implement pushQr() method.
    }

    public function sendUsers(string $content) {
        // TODO: Implement sendUsers() method.
    }

    public function sendGroup(string $group, string $content) {
        // TODO: Implement sendGroup() method.
    }

    public function sendAnyUsers(array $openid, string $content) {
        // TODO: Implement sendAnyUsers() method.
    }

    public function sendTemplate(string $openid, array $data) {
        // TODO: Implement sendTemplate() method.
    }

    public function pullTemplate(callable $cb)
    {
        // TODO: Implement pullTemplate() method.
    }

    public function pushMedia(MediaModel $model)
    {
        // TODO: Implement pushMedia() method.
    }

    public function pushNews(MediaModel $model)
    {
        // TODO: Implement pushNews() method.
    }

    public function pullMedia(string $type)
    {
        // TODO: Implement pullMedia() method.
    }

    public function deleteMedia(string $mediaId)
    {
        // TODO: Implement deleteMedia() method.
    }

    public function oAuthType(): string
    {
        // TODO: Implement oAuthType() method.
    }

    public function receive(): array
    {
        // TODO: Implement receive() method.
    }

    public function reply(Output $output, array $data)
    {
        // TODO: Implement reply() method.
    }
}

