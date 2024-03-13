<?php
declare(strict_types=1);
namespace Module\Bot\Domain\Adapters;

use Module\Bot\Domain\Model\MediaModel;
use Module\Bot\Domain\Model\QrcodeModel;
use Module\Bot\Domain\Model\BotModel;

interface IPlatformAdapter extends IReplyAdapter {

    public function __construct(BotModel $platform);

    public function pullUser(string $openId): array;

    public function pullUsers(callable $cb);

    public function pushMenu(array $items);

    public function pushQr(QrcodeModel $model): array;

    public function sendUsers(string $content);
    public function sendGroup(string $group, string $content);
    public function sendAnyUsers(array $openid, string $content);

    public function sendTemplate(string $openid, array $data);

    public function pullTemplate(callable $cb);

    public function pushMedia(MediaModel $model);
    public function pushNews(MediaModel $model);

    public function pullMedia(string $type);

    public function deleteMedia(string $mediaId);

}