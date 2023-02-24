<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Scene;

use Module\WeChat\Domain\MessageReply;
use Module\WeChat\Domain\Model\ReplyModel;

interface SceneInterface {

    public function messageProvider(MessageReply $message);
    /**
     * @return ReplyModel
     */
    public function enter(string $content);

    public function leave();

    public function __invoke(string $content);
}