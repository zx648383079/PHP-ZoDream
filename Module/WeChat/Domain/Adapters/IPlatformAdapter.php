<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Adapters;

use Module\WeChat\Domain\Model\MediaModel;
use Module\WeChat\Domain\Model\QrcodeModel;
use Module\WeChat\Domain\Model\WeChatModel;

interface IPlatformAdapter extends IReplyAdapter {

    public function __construct(WeChatModel $platform);

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